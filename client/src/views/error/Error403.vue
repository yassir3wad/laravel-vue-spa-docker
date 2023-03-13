<template>
  <div class="misc-wrapper">
    <b-link class="brand-logo">
      <vuexy-logo/>
      <h2 class="brand-text text-primary ml-1">
        Vuexy
      </h2>
    </b-link>

    <div class="misc-inner p-2 p-sm-3">
      <div class="w-100 text-center">
        <h2 class="mb-1">
          You are not authorized! 🔐
        </h2>
        <p class="mb-2">
          You don’t have permission to access this page. Go Home!!
        </p>
        <b-button
            variant="primary"
            class="mb-1 btn-sm-block"
            :to="{name: 'Home'}"
        >Back to Home
        </b-button>
        <b-img
            fluid
            :src="imgUrl"
            alt="Not authorized page"
        />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable global-require */
import VuexyLogo from '@core/layouts/components/Logo.vue'

export default {
  components: {
    VuexyLogo,
  },
  data() {
    return {
      downImg: require('@/assets/images/pages/not-authorized.svg'),
    }
  },
  computed: {
    imgUrl() {
      if (this.$store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.downImg = require('@/assets/images/pages/not-authorized-dark.svg')
        return this.downImg
      }
      return this.downImg
    },
  },
  mounted() {
    document.title = 403;
  }

}
</script>

<style lang="scss">
@import '@core/scss/vue/pages/page-misc.scss';
</style>
