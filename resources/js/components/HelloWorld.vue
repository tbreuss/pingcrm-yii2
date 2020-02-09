<template>
    <div class="hello">
        <h1>{{ msg }}</h1>
    </div>
</template>

<script>
    export default {
        name: 'HelloWorld',
        props: {
            msg: String
        }
    }
</script>

<style scoped>
    h1 {
        margin: 40px 0 0;
        color: #41B883;
    }
</style>