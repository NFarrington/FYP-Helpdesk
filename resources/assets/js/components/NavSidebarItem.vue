<template>
    <li v-bind:class="{ active: isActive }"><a v-bind:href="route">{{ name }}</a></li>
</template>

<script>
    export default {
        props: ['route', 'name'],
        computed: {
            isActive: function () {
                return window.location.href == this.route;
            }
        }
    }
</script>
