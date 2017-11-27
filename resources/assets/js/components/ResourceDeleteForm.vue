<template>
    <div style="display: inline-block;">
        <button type="submit" class="btn btn-xs btn-danger" v-on:click="submit()">Delete</button>
        <form class="hidden" method="POST" v-bind:action="route">
            <input type="hidden" name="_token" v-bind:value="csrfToken">
            <input type="hidden" name="_method" value="DELETE">
        </form>
    </div>
</template>

<script>
    export default {
        props: ['route'],
        methods: {
            submit: function () {
                if (!confirm('Are you sure you want to delete this object?')) return;
                this.$el.querySelector('form').submit();
            }
        },
        computed: {
            csrfToken: function () {
                return $('meta[name=csrf-token]').attr('content');
            }
        }
    }
</script>
