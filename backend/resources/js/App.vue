<template>
    <div>
        vue app
        <button @click.stop="increment()">increment</button>
        <input type="date" v-model="date">
        
        <h1> {{test}}</h1>
        <h2>{{date}}</h2>

         <h1>GeeksforGeeks</h1>
      
    <h3>
        Get input date in
        dd-mm-yyyy format
    </h3>
      
    <label for="start">
        Enter the Date:
    </label>
  
    <input type="date" name="begin" 
        placeholder="dd-mm-yyyy" value=""
        min="1997-01-01" max="2030-12-31">

    </div>
</template>

<script>
    export default {
        data() {
            return {
                test: 0,
                date:''
            }
        },

        methods: {
            increment() {
                this.test++;
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>