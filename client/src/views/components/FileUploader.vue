<template>
  <b-form-group :label="label">
    <file-pond
        name="file"
        ref="pond"
        :class="{ 'error' : errors && errors.length }"
        label-idle="Drop files here..."
        :allowMultiple="allowMultiple"
        :allowReorder="allowReorder"
        credits="false"
        :storeAsFile="false"
        :maxFiles="maxFiles"
        :maxParallelUploads="1"
        :accepted-file-types="mimes"
        :server="server"
        required
        instantUpload="true"
        allowImagePreview="true"
        allowFileSizeValidation="true"
        :maxFileSize="maxFileSize + 'MB'"
        chunkUploads="false"
        imagePreviewHeight="200"
        @removefile="filesUpdated"
        @processfile="filesUpdated"
    />
    <small class="text-danger d-block" style="margin-top: -8px" v-if="errors && errors.length">{{ errors[0] }}</small>
  </b-form-group>
</template>

<script>
import vueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import axios from 'axios'

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginFileValidateSize);

export default {
  name: "file-uploader",
  components: {FilePond},
  props: {
    label: {
      type: String,
      default() {
        return 'Image';
      },
    },
    serverKey: {
      type: String,
      required: true
    },
    errors: {
      type: Array
    },
    allowMultiple: {
      type: Boolean,
      default() {
        return false;
      },
    },
    allowReorder: {
      type: Boolean,
      default() {
        return false;
      },
    },
    maxFiles: {
      type: Number,
      default() {
        return 1;
      },
    },
    maxFileSize: {
      type: Number,
      default() {
        return 4;
      },
    },
    mimes: {
      type: String,
      default() {
        return 'image/jpeg, image/png';
      },
    },
    value: {
      type: Array,
      default() {
        return []
      },
      validator(val) {
        return val.filter(item => !item.url || !item.path).length === 0;
      }
    }
  },
  data() {
    return {
      server: {
        process: (fieldName, file, metadata, load, error, progress, abort) => {
          const formData = new FormData();
          formData.append(fieldName, file, file.name);
          formData.append('type', this.serverKey);

          const controller = new AbortController();

          this.$http.post('/api/files', formData, {
            headers: {'Content-Type': 'multipart/form-data'},
            signal: controller.signal,
            onUploadProgress: (e) => {
              progress(e.lengthComputable, e.loaded, e.total);
            }
          }).then(response => {
            load(response.data.path);
          }).catch(e => {
            error(e);
          });

          // Setup abort interface
          return {
            abort: () => {
              controller.abort()
              abort();
            }
          };
        },

        load: (source, load, error, progress, abort, headers) => {
          const controller = new AbortController();
          // fetch(source)
          //     .then((response) => response.blob())
          //     .then(load)
          //     .catch(error);
          axios.get(source, {
            signal: controller.signal,
            responseType: 'blob',
            onUploadProgress: (e) => {
              progress(e.lengthComputable, e.loaded, e.total);
            }
          })
              .then(res => {
                return res.data
              })
              .then(load)
              .catch(error);

          return {
            abort: () => {
              controller.abort()
              abort();
            }
          };
        },
        fetch: null,
        revert: null,
        remove: null,
      },
      files: this.value
    }
  },
  methods: {
    filesUpdated() {
      this.files = this.$refs.pond.getFiles().map(file => {
        return {
          path: file.getMetadata('path') ? file.getMetadata('path') : file.serverId,
          url: file.getMetadata('url') ? file.getMetadata('url') : file.serverId
        };
      }).filter(item => item && item.path);
    }
  },
  watch: {
    files: {
      deep: true,
      handler(val) {
        this.$emit('input', val);
      }
    },
    value: {
      immediate: true,
      deep: true,
      handler(val) {
        this.files = val;
      }
    }
  },
  mounted() {
    this.files.forEach(file => {
      this.$refs.pond.addFile(file.url, {
        metadata: {
          path: file.path,
          url: file.url,
        },
        type: 'local'
      });
    });
  }
}
</script>

<style>

</style>