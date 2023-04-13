<template>
    <MainLayout>
        <template #content>
            Dashboard page...
        </template>
    </MainLayout>
</template>

<style scoped>
#login-form {
  margin-top: 50%;
}
.text-align-center {
  text-align: center;
}
</style>

<script>
import { defineComponent, onMounted } from 'vue';
import { useRouter } from 'vue-router'
import { useStore } from 'vuex';
import _ from 'lodash';

export default defineComponent({
  setup() {
    const router = useRouter()
    const store = useStore();

    onMounted(() => {
        // redirect after getting user details
        router.push({ 'name' : 'product-list' });
    })

    return {};
  },
});
</script>