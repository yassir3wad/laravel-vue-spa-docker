<template>
  <b-row
      v-if="breadcrumbs.length || pageTitle"
      class="content-header"
  >

    <!-- Content Left -->
    <b-col
        class="content-header-left mb-2"
        cols="12"
        md="9"
    >
      <b-row class="breadcrumbs-top">
        <b-col cols="12">
          <h2 class="content-header-title float-left pr-1 mb-0">
            {{ pageTitle }}
          </h2>
          <div class="breadcrumb-wrapper">
            <b-breadcrumb>
              <b-breadcrumb-item to="/">
                <feather-icon
                    icon="HomeIcon"
                    size="16"
                    class="align-text-top"
                />
              </b-breadcrumb-item>
              <b-breadcrumb-item
                  v-for="item in breadcrumbs"
                  :key="item.text"
                  :active="item.active"
                  :to="item.to"
              >
                {{ item.text }}
              </b-breadcrumb-item>
            </b-breadcrumb>
          </div>
        </b-col>
      </b-row>
    </b-col>
  </b-row>
</template>

<script>
import Ripple from 'vue-ripple-directive'
import {mapGetters} from 'vuex'

export default {
  directives: {
    Ripple,
  },
  computed: {
    ...mapGetters([
      "breadcrumbs",
      "pageTitle",
    ]),
  },
  watch: {
    pageTitle: {
      immediate: true,
      handler(val) {
        document.title = val;
      }
    }
  }
}
</script>
