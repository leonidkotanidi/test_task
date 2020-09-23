<template>
    <div id="container">
        <div id="header">
            <div id="logo">
                Logo
            </div>
            <div id="menu">
                <a href="#">Home</a>
                <a href="#">About us</a>
            </div>
        </div>
        <div id="main">
            <h1>Items Management Page</h1>

            <div id="add_box">
                <input v-model="name" type="text" name="name" placeholder="Enter Item Name and Click Add">
                <input type="submit" value="Add" @click="addItem">
                <p class="error" v-if="errors.name"> {{ errors.name }}</p>
            </div>


            <div style="display: flex">
                <div>
                    <div class="box">
                        <button
                            v-for="item in items"
                            v-if="!item.selected"
                            :key="item.id"
                            @click="selectItem(item)"
                            :class="selectedItem && selectedItem.id === item.id? 'selected' : ''"
                        >
                            {{item.name}}
                        </button>
                    </div>
                    <p class="error" v-if="errors.selected.left"> {{ selectedItem? errors.selected.left : '' }} </p>
                </div>

                <div class="change">
                    <button @click="makeSelected"> ></button>
                    <button @click="deleteSelected"> <</button>
                </div>

                <div>
                    <h3>Selected Items:</h3>
                    <div class="box">
                        <button
                            v-for="item in items"
                            v-if="item.selected"
                            :key="item.id"
                            @click="selectItem(item)"
                            :class="selectedItem && selectedItem.id === item.id? 'selected' : ''"
                        >
                            {{item.name}}
                        </button>
                    </div>
                    <p class="error" v-if="errors.selected.right"> {{ selectedItem? errors.selected.right : '' }} </p>
                </div>
            </div>

            <p class="error" v-if="items && items.length !== 0 && errors.selected.all"> {{ !selectedItem ? errors.selected.all: '' }} </p>
        </div>
        <hr>

        <div id="footer">
            <a href="#">Home</a>
            <a href="#">About us</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "index",
        data() {
            return {
                items: null,
                name: null,
                selectedItem: null,
                errors: {
                    name: '',
                    selected: {
                        all: '',
                        left: '',
                        right: '',
                    }
                }
            }
        },
        created() {
            this.getItems();
        },
        methods: {
            getItems() {
                axios.get('/items').then((response) => {
                    this.items = response.data.items;
                });
            },
            addItem() {
                this.selectedItem = null
                this.errors.selected.all = ''
                this.errors.selected.left = ''
                this.errors.selected.right = ''
                if(this.name){
                    this.errors.name = ''
                    axios.post('/items', {name: this.name})
                        .then((response) => {
                            if(response.data.success === false){
                                this.errors.name = response.data.errors.name[0]
                                return
                            }
                            this.items.push(response.data.item);
                            this.name = null
                            this.errors.name = ''
                        })
                }else{
                    this.errors.name = 'The name field is required.'
                }
            },
            selectItem(item) {
                this.errors.name = ''
                this.selectedItem = this.selectedItem && this.selectedItem.id == item.id ?  null : item;
            },
            makeSelected() {
                this.errors.name = ''
                if (this.selectedItem){
                    this.errors.selected.all = ''
                    this.errors.selected.left = ''
                    if(!this.selectedItem.selected) {
                        this.errors.selected.right = ''
                        axios.put('items/' + this.selectedItem.id).then((response) => {
                            this.selectedItem = null;
                            this.getItems();
                        });
                    }else{
                        this.errors.selected.right = 'Please select item from left box'
                    }
                }else{
                    this.errors.selected.all = 'Please select item.'
                }
            },
            deleteSelected() {
                this.errors.name = ''
                if (this.selectedItem){
                    this.errors.selected.all = ''
                    this.errors.selected.right = ''
                    if(this.selectedItem.selected) {
                        this.errors.selected.left = ''
                        axios.put('items/' + this.selectedItem.id).then(() => {
                            this.selectedItem = null;
                            this.getItems();
                        });
                    }else{
                        this.errors.selected.left = 'Please select item from right box'
                    }
                }else{
                    this.errors.selected.all = 'Please select item.'
                }
            },
        },
    }
</script>

<style scoped>
    html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    #container {
        padding: 0 200px;
    }

    #header {
        padding: 20px 0;
    }

    #logo {
        float: left;
    }

    #menu {
        float: right;
    }

    #main {
        width: 80%;
        margin: 20px auto;
        padding-bottom: 20px;
    }
    a {
        margin-left: 10px;
    }

    input[type=text]{
        width: 240px;
        height: 40px;
    }
    input[type=submit]{
        width: 55px;
        height: 40px;
    }

    h3 {
        margin: 0;
    }

    .box {
        width: 300px;
        min-height: 200px;
        border: 1px solid;
    }
    #main div:nth-of-type(1){
        margin-top: 30px;
    }
    .box button {
        display: block;
        width: 100%;
        height: 40px;
        background: none;
        border: none;
    }
    .box .selected {
        background: #2aa2ff;
        border: none;
        color: white
    }

    .change {
        width: 50px;
        height: 100px;
        margin: 0 20px;
        padding-top: 100px;
    }
    .change button {
        display: block;
        width: 40px;
        height: 30px;
        line-height: 20px;
        margin: 10px auto;
    }

    #footer {
        width: 80%;
        margin: 20px auto;
    }

    .error {
        color: red;
    }
</style>
