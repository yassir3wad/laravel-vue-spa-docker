<template>
  <VuePhoneNumberInput :fetch-country="false"
                       :no-use-browser-locale="true" @update="onMobileUpdate"
                       v-model="internalValue"
                       :error="errors.length > 0 ? true : null"/>
</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
  name: 'mobile',
  components: {VuePhoneNumberInput},
  props: {
    errors: {
      type: Array
    },
    value: {
      default() {
        return null;
      }
    },
  },
  data() {
    return {
      internalValue: null
    }
  },
  methods: {
    onMobileUpdate(e) {
      if (this.value !== e.formattedNumber) {
        this.$emit('input', e.formattedNumber)
      }
    }
  },
  watch: {
    value: {
      handler(val) {
        this.internalValue = val;
      }
    }
  }
}
</script>

<style>
</style>