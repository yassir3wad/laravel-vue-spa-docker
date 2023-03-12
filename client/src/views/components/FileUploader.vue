<template>
  <file-pond
      name="file"
      ref="pond"
      :class="{ 'error' : errors && errors.length }"
      :label-idle="$t('Drop File')"
      maxParallelUploads="1"
      maxFiles="1"
      :accepted-file-types="mimes"
      :server="server"
      allowMultiple="false"
      credits="false"
      :allowProcess="instantUpload"
      :instantUpload="instantUpload"
      checkValidity="true"
      allowImagePreview="true"
      allowFileSizeValidation="true"
      chunkUploads="false"
      :maxFileSize="maxFileSize + 'MB'"
      :stylePanelLayout="layout"
      @removefile="fileRemoved"
      @processfile="fileProcessed"
      @addfile="fileAdded"
  />
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
    serverKey: {
      type: String,
    },
    errors: {
      type: Array
    },
    instantUpload: {
      type: Boolean,
      default() {
        return false;
      },
    },
    maxFileSize: {
      type: Number,
      default() {
        return 2;
      },
    },
    mimes: {
      type: String,
      default() {
        return 'image/jpeg, image/png';
      },
    },
    value: {
      default() {
        return null;
      }
    },
    layout: {
      default() {
        return 'compact';
      },
      validator(val) {
        return val === 'circle' || val === 'integrated' || val === 'compact';
      }
    },
  },
  data() {
    return {
      server: {
        process: (fieldName, file, metadata, load, error, progress, abort) => {
          if (!this.serverKey) {
            throw new Error('serverKey prop is required');
          }

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
      internalValue: null
    }
  },
  methods: {
    fileProcessed(error, file) {
      if (error) {
        this.internalValue = null;
      } else {
        this.internalValue = {
          path: file.getMetadata('path') ? file.getMetadata('path') : file.serverId,
          url: file.getMetadata('url') ? file.getMetadata('url') : file.serverId
        };
      }
    },
    fileRemoved(error, file) {
      this.internalValue = null;
    },
    fileAdded(error, file) {
      if (this.instantUpload) return;

      if (error) {
        this.internalValue = null;
      } else if (file.origin === 1) {
        // added by the user
        this.internalValue = file.file;
      }
    },
    handleInput() {
      if (this.internalValue) {
        if (this.instantUpload) {
          this.handleInstantUpload();
        } else {
          this.handleFormUpload();
        }
      } else {
        this.clear();
      }
    },
    handleInstantUpload() {
      if (typeof this.internalValue !== 'object' || !this.internalValue.url || !this.internalValue.path) {
        throw new Error('value must be of type object{path, url}')
      }

      this.$refs.pond.addFile(this.internalValue.url, {
        metadata: {
          path: this.internalValue.path,
          url: this.internalValue.url,
        },
        type: 'local'
      })
    },
    handleFormUpload() {
      if (typeof this.internalValue === 'string') {
        this.$refs.pond.addFile(this.internalValue, {
          type: 'local'
        });
      }
    },
    clear() {
      this.$refs.pond.removeFiles();
    }
  },
  watch: {
    internalValue: {
      handler(val) {
        this.handleInput();
        this.$emit('input', val);
      }
    },
    value: {
      immediate: true,
      handler(val) {
        this.internalValue = val;
      }
    }
  }
}
</script>