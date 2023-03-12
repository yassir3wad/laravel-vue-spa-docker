// action types
// mutation types
export const SET_BREADCRUMB = 'setBreadcrumb';

export default {
    state: {
        breadcrumbs: [],
    },
    getters: {
        /**
         * Get list of breadcrumbs for current page
         * @param state
         * @returns {*}
         */
        breadcrumbs(state) {
            return state.breadcrumbs;
        },

        /**
         * Get the page title
         * @param state
         * @returns {*}
         */
        pageTitle(state) {
            let last = state.breadcrumbs[state.breadcrumbs.length - 1];
            if (last && last.text) {
                return last.text;
            }
        },
    },
    actions: {
        /**
         * Set the breadcrumbs list
         * @param state
         * @param payload
         */
        [SET_BREADCRUMB](state, payload) {
            state.commit(SET_BREADCRUMB, payload);
        },
    },
    mutations: {
        [SET_BREADCRUMB](state, payload) {
            state.breadcrumbs = payload;
        },
    },
};
