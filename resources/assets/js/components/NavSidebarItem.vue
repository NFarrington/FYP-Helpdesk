<template>
    <li v-bind:class="{ active: isActive }"><a v-bind:href="route">{{ name }}</a></li>
</template>

<script>
    export default {
        props: ['route', 'name', 'active'],
        computed: {
            isActive: function () {
                return this.active || window.location.href.replace(/\/$/, '') === this.route;
            }
        }
    }
</script>
