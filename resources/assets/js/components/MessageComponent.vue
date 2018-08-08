<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <strong :class="{'text-danger':chat_block}">
                {{friend.name}}
                <span v-if="chat_block">(Blocked)</span>
            </strong>
            
            <a href="" @click.prevent="close">
                <i class="fa fa-times float-right" aria-hidden="true"></i>
            </a>
            <div class="dropdown pull-right mr-4 dropleft">
                <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" @click.prevent="unblock" v-if="chat_block">Unblock</a>
                    <a class="dropdown-item" href="#" @click.prevent="block" v-else>Block</a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear Chats</a>
                </div>
            </div>
            
        </div>
        <div class="card-body" v-chat-scroll>
           <p class="card-text" :class="{'text-right':chat.type== 0}" v-for="chat in chats" :key="chat.id">
               {{chat.message}}
           </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="input-group mb-3">
                <input type="text" :disabled="chat_block" v-model="message" class="form-control" placeholder="Write your message herer....">
                <div class="input-group-prepend">
                    <input type="submit" :disabled="chat_block" class="btn btn-success" value="Send">
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props:['friend'],
        data(){
            return{
                chats:[],
                message:null,
                chat_block:false
            }
        },
        methods:{
            send(){
                this.pushToChat(this.message);
                axios.post(`/send/${this.friend.session.id}`,{
                    content:this.message,
                    to_user:this.friend.id
                });
                this.message = null;
            },
            pushToChat(message){
                this.chats.push({message:message,type:0,send_at:"Just Now"});
            },
            close(){
                this.$emit('close');
            },
            clear(){
                this.chats=[]
            },
            block(){
                this.chat_block= true
            },
            unblock(){
                this.chat_block=false
            },
            getAllMessages(){
                axios.post(`/session/${this.friend.session.id}/chats`)
                .then(res=>(this.chats=res.data.data));
            }
        },
        created(){
            this.getAllMessages();
        }
    }
</script>

<style>
.chat-box{
    height: 400px;
}
.card-body{
    overflow-y: scroll;
}
.send{
    float: left;
}
.send-box{
    width: 100%;
}
.text-white{
    border-radius: 8px;
    padding: 5px;
    margin: 5px;
    background-color: blue;
}
</style>
