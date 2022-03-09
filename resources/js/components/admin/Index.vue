<template>
    <div>
        <HeaderAdmin/>
        <router-view></router-view>
        <FooterAdmin/>
    </div>
</template>
<script>
import HeaderAdmin from './Header.vue';
import FooterAdmin from './Footer.vue';
export default {
    components: {
        HeaderAdmin,
        FooterAdmin
    }
};
</script>
<style></style>
