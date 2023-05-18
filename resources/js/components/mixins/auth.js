
  export default {

    mounted () {
        if (this.$cookies.get('user') == null) {
            this.$router.replace('/');
        }
      },
    computed: {
        isAuthenticate() {
            return this.$cookies.get('user') ? true : false;
        },
        userName(){
            return this.$cookies.get('user') 
        }
    },
};