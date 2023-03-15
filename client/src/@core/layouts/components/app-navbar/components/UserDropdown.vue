<template>
  <b-nav-item-dropdown
      right
      toggle-class="d-flex align-items-center dropdown-user-link"
      class="dropdown-user"
  >
    <template #button-content>
      <div class="d-sm-flex d-none user-nav" v-if="user">
        <p class="user-name font-weight-bolder mb-0">
          {{ user.name }}
        </p>
        <span class="user-status">@{{ user.username }}</span>
      </div>
      <b-avatar
          v-if="user"
          size="40"
          :src="user.image"
          variant="light-primary"
          badge
          class="badge-minimal"
          badge-variant="success"
      >
      </b-avatar>
    </template>

    <b-dropdown-item
        :to="{name: 'profile'}"
        link-class="d-flex align-items-center"
    >
      <feather-icon
          size="16"
          icon="UserIcon"
          class="mr-50"
      />
      <span>Profile</span>
    </b-dropdown-item>
<!--    <b-dropdown-item-->
<!--        :to="{ name: 'apps-email' }"-->
<!--        link-class="d-flex align-items-center"-->
<!--    >-->
<!--      <feather-icon-->
<!--          size="16"-->
<!--          icon="MailIcon"-->
<!--          class="mr-50"-->
<!--      />-->
<!--      <span>Inbox</span>-->
<!--    </b-dropdown-item>-->
<!--    <b-dropdown-item-->
<!--        :to="{ name: 'apps-todo' }"-->
<!--        link-class="d-flex align-items-center"-->
<!--    >-->
<!--      <feather-icon-->
<!--          size="16"-->
<!--          icon="CheckSquareIcon"-->
<!--          class="mr-50"-->
<!--      />-->
<!--      <span>Task</span>-->
<!--    </b-dropdown-item>-->
<!--    <b-dropdown-item-->
<!--        :to="{ name: 'apps-chat' }"-->
<!--        link-class="d-flex align-items-center"-->
<!--    >-->
<!--      <feather-icon-->
<!--          size="16"-->
<!--          icon="MessageSquareIcon"-->
<!--          class="mr-50"-->
<!--      />-->
<!--      <span>Chat</span>-->
<!--    </b-dropdown-item>-->

<!--    <b-dropdown-divider/>-->

<!--    <b-dropdown-item-->
<!--        :to="{ name: 'pages-account-setting' }"-->
<!--        link-class="d-flex align-items-center"-->
<!--    >-->
<!--      <feather-icon-->
<!--          size="16"-->
<!--          icon="SettingsIcon"-->
<!--          class="mr-50"-->
<!--      />-->
<!--      <span>Settings</span>-->
<!--    </b-dropdown-item>-->
<!--    <b-dropdown-item-->
<!--        :to="{ name: 'pages-pricing' }"-->
<!--        link-class="d-flex align-items-center"-->
<!--    >-->
<!--      <feather-icon-->
<!--          size="16"-->
<!--          icon="CreditCardIcon"-->
<!--          class="mr-50"-->
<!--      />-->
<!--      <span>Pricing</span>-->
<!--    </b-dropdown-item>-->
<!--    <b-dropdown-item-->
<!--        :to="{ name: 'pages-faq' }"-->
<!--        link-class="d-flex align-items-center"-->
<!--    >-->
<!--      <feather-icon-->
<!--          size="16"-->
<!--          icon="HelpCircleIcon"-->
<!--          class="mr-50"-->
<!--      />-->
<!--      <span>FAQ</span>-->
<!--    </b-dropdown-item>-->
    <b-dropdown-item
        link-class="d-flex align-items-center"
        @click="logout"
    >
      <feather-icon
          size="16"
          icon="LogOutIcon"
          class="mr-50"
      />
      <span>Logout</span>
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  data() {
    return {
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('auth/logout').finally(() => {
        this.$router.push({name: 'login'})
      })
    },
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    })
  },
}
</script>
