<template>
    <div class="card card-default chat-box">
        <div class="card-header message-header">
            <strong :class="{'text-danger':session.block}">
                {{friend.name}} <span v-if="isTyping">is typing ...</span>
                <span v-if="session.block">(Blocked)</span>
            </strong>
            
            <a href="" @click.prevent="close" style="color:white;">
                <i class="fa fa-times float-right" aria-hidden="true"></i>
            </a>
            <div class="dropdown pull-right mr-4 dropleft">
                <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white;">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" v-if="session.block && can" @click.prevent="unblock" >Unblock</a>
                    <a class="dropdown-item" href="#" @click.prevent="block" v-if="!session.block">Block</a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear Chats</a>
                </div>
            </div>
            
        </div>
        <div class="card-body" v-chat-scroll>
           <p class="card-text" :class="{'text-right':chat.type == 0,}" v-for="chat in chats" :key="chat.id">
               <span :class="{'send-msg':chat.type == 0, 'rcv-msg':chat.type == 1}">{{chat.message}}</span>
               <br/>
                <span style="font-size:8px; color: black; margin-right:10px;">{{chat.read_at}}</span>
           </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="input-group mb-3">
                <input type="text" :disabled="session.block" v-model="message" class="form-control" placeholder="Write your message herer....">
                <div class="input-group-prepend">
                    <input type="submit" :disabled="session.block" class="btn btn-success" value="Send">
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
                isTyping:false
            }
        },
        
        computed:{
            can(){
                return this.session.blocked_by == auth.id;
            },
            session(){
                return this.friend.session;
            }
        },
        watch:{
            message(value){
                if(value){
                    Echo.private(`Chat.${this.friend.session.id}`)
                    .whisper('typing', {
                        name: auth.name
                    });
                }
            }
        },
        methods:{
            send(){
                this.pushToChat(this.message);
                axios.post(`/send/${this.friend.session.id}`,{
                    content:this.message,
                    to_user:this.friend.id
                }).then(res=> (this.chats[this.chats.length -1].id = res.data));
                this.message = null;
            },
            pushToChat(message){
                this.chats.push({
                    message:message,
                    type:0,
                    read_at:null,
                    send_at:"Just Now"
                });
            },
            close(){
                this.$emit('close');
            },
            clear(){
                axios.post(`/session/${this.friend.session.id}/clear`)
                .then(res => (this.chats=[]));
            },
            block(){
                this.session.block = true;
                axios
                .post(`/session/${this.friend.session.id}/block`)
                .then(res => (this.session.blocked_by=auth.id));
            },
            unblock(){
                this.session.block = false;
                axios
                .post(`/session/${this.friend.session.id}/unblock`)
                .then(res=> (this.session.blocked_by=null));
            },
            getAllMessages(){
                axios.post(`/session/${this.friend.session.id}/chats`)
                .then(res=>(this.chats=res.data.data));
            },
            read(){
                axios.post(`/session/${this.friend.session.id}/read`);
            }
        },
        created(){
            this.read();

            this.getAllMessages();

            Echo.private(`Chat.${this.friend.session.id}`).listen(
                "PrivatechatEvent",
                e =>{
                    this.friend.session.open ? this.read():"";
                    this.chats.push({message : e.content,type:1,send_at:"Just Now"});
                }
            );
            Echo.private(`Chat.${this.friend.session.id}`).listen(
                "MessageReadEvent",
                e => this.chats.forEach(
                    chat=> (chat.id == e.chat.id ? (chat.read_at = e.chat.read_at):"")
                )
            );
            Echo.private(`Chat.${this.friend.session.id}`).listen(
                "BlockEvent",
                e => (this.session.block = e.blocked)
            );
            Echo.private(`Chat.${this.friend.session.id}`).listenForWhisper(
                'typing',
                e => {
                    this.isTyping = true
                    setTimeout(()=>{
                        this.isTyping = false
                    }, 2000);
                }
            );
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
.send-msg{
    display: inline-block;
    clear: both;
    background: #2a81d3;
    color: #f5f5f5;
    padding: 10px;
    border-radius: 20px;
    max-width: 80%;
    margin: 0px;
}
.rcv-msg{
    display: inline-block;
    clear: both;
    background: #cbccce;
    color: black;
    padding: 10px;
    border-radius: 20px;
    max-width: 80%;
    margin: 0px;
}
.message-header{
    background-color: #2c3e50;
    color: #f5f5f5;
}
</style>
