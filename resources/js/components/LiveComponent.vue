<template>
   <div class="row">
      <div class="intercom-conversation-background"></div>
      <div class="video text-center">
        <iframe src="https://livelss.bj.bcebos.com/qn.html?stream=classroom&vid=2020&preset=L3" frameborder="0"></iframe>
      </div>

      <div class="col">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">消息</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">信息</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">在线用户</a>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">

          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
             
            <div class="tab-wrapper">
                <div class="list-unstyled scroll-list" v-chat-scroll>
                    <div class="li" v-bind:class="checkCodes(message.user_id)" v-for="(message, index) in messages" :key="index" >
                      <div class="intercom-comment-container" >
                        <div class="intercom-comment-container-admin-avatar">
                          <div class="intercom-avatar">
                            <img src="http://wx.qlogo.cn/mmopen/50HcP4UOeLWI7mH6BLL0RQNBBJNclX738qVZ1b9CIRGK8eBKzV5RghHRq2vWekUt6EiaPLq9lSlHibWLEmt7nLl2tIZS4iabwtN/0">
                          </div>
                        </div>
                        <div class="intercom-comment">
                          <div class="intercom-block-paragraph">
                            {{ message.message }}
                          </div>
                        </div>
                      </div>
                      <div class="intercom-conversation-part-metadata">{{ message.user.name }}</div>
                  </div>
                </div>
                  
                <div class="intercom-conversation-footer">
                  <div class="intercom-composer">
                    <textarea
                        id="intercom-textarea"
                        @keydown="sendTypingEvent"
                        @keyup.enter="sendMessage"
                        v-model="newMessage"
                        type="textarea"
                        name="message"
                        placeholder="发布消息..."></textarea>
                    <button class="intercom-composer-send-button"></button>
                  </div>
                </div>
            </div>
            
          </div>

          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="tab-wrapper">
                <span class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing...</span>
                <ul>
                    <li class="py-2" v-for="(user, index) in users" :key="index">
                        {{ user.name }}
                    </li>
                </ul>
            </div>
          </div>

          <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <h1>{{live.title}}</h1>
            <p class="description">
              {{live.description}}
            </p>
          </div>
        </div>

      </div>

   </div>
</template>

<script>
    export default {
        props:['user', 'live'],
        data() {
            return {
                messages: [],
                newMessage: '',
                users:[],
                activeUser: false,
                typingTimer: false,
                room: 'live.' + this.live.id
            }
        },
        computed: {
        },
        created() {
            this.fetchMessages();
            Echo.join(this.room)
                .here(user => {
                    this.users = user;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('MessageSent', (event) => {
                    this.messages.push(event.message);
                })
                .listenForWhisper('typing', user => {
                   this.activeUser = user;
                    if(this.typingTimer) {
                        clearTimeout(this.typingTimer);
                    }
                   this.typingTimer = setTimeout(() => {
                       this.activeUser = false;
                   }, 3000);
                })
        },
        methods: {
            checkCodes: function(user_id) {
              return user_id == this.user.id?'right float-right':'';
            },
            fetchMessages() {
                axios.get('/messages/'+this.live.id).then(response => {
                    this.messages = response.data;
                })
            },
            sendMessage() {
                this.messages.push({
                    user: this.user,
                    user_id: this.user.id,
                    live: this.live,
                    message: this.newMessage
                });
                axios.post('/messages', {message: this.newMessage, live: this.live.id});
                this.newMessage = '';
            },
            sendTypingEvent() {
                Echo.join(this.room)
                    .whisper('typing', this.user);
            }
        }
    }
</script>

<style>
  .body {
    height: 100vh;
  }
  .tab-wrapper{
    border-top: none;
    border-radius: 0;
  }
  .video{
    width: 100%;
  }
  ::-webkit-scrollbar {
      width: 12px;
  }
   
  ::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
      border-radius: 10px;
  }
   
  ::-webkit-scrollbar-thumb {
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
  }

  .intercom-composer{
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    min-height: 55px;
    max-height: 200px;
  }
  .intercom-conversation-footer{
    opacity: 1;
    animation-delay: 80ms;
    animation-timing-function: cubic-bezier(.23, 1, .32, 1);
    animation-duration: .32s;

    position: fixed;
    bottom: -8px;
    left: 0;
    right: 0;
    border-radius: 0 0 6px 6px;
  }
  
  .scroll-list{
    height:60vh; 
    overflow-y:scroll
  }
  .intercom-conversation-background{
    background-image: url(https://live.yongbuzhixi.com/images/background-1@2x.aea5e218.png);
    background-size: 417px 417px;
    background-repeat: repeat;
    position: absolute;
    top: 55px;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #fff;
    opacity: .8;
  }
  #intercom-textarea{
    width: 100%;
    bottom: 0;
    left: 0;
    color: #565867;
    background-color: #f0f3f5;
    resize: none;
    border: none;
    transition: background-color .2s ease, box-shadow .2s ease;

    box-sizing: border-box;
    padding: 10px;
    padding-right: 70px;
    padding-left: 30px;
    height: 100%;
    font-family: "intercom-font", "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.33;
    white-space: pre;
    white-space: pre-wrap;
    word-wrap: break-word;
    cursor: text;
  }
  .intercom-composer-send-button {
    background-image: url(https://live.yongbuzhixi.com/images/mobile-send@2x.5a60f4ee.png);
    background-size: 14px 14px;
    background-repeat: no-repeat;
    background-position: 11.5px;
    position: absolute;
    border-radius: 50%;
    bottom: 18px;
    right: 30px;
    width: 36px;
    height: 36px;
    opacity: 1;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .2);
    background-color: #1F8CEB;
    transition: opacity .12s ease-in;
  }

  .intercom-comment-container{
    padding-left: 40px;
    position: relative;
    max-width: 85%;
  }
  .intercom-comment-container-admin-avatar {
    position: absolute;
    left: 0;
    bottom: 10px;
  }
  .intercom-comment{
    color: #606273;
    background-color: #eff3f6;
    padding: 10px 15px;
    border-radius: 4px;
    width: fit-content;
  }
  .intercom-block-paragraph{
    margin-bottom: 0;
    font-size: 14px;
    line-height: 1.4;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
  }
  .li{
    padding-bottom: 15px;
    clear: both;
  }

  .intercom-conversation-part-metadata{
    padding-left: 40px;
    clear: both;
    color: #a8b6c2;
    font-size: 12px;
    padding-top: 5px;
  }

  .intercom-avatar{
    width: 30px;
    height: 30px;
    line-height: 30px;
    font-size: 15px;
    margin: 0 auto;
    border-radius: 50%;
    display: inline-block;
    vertical-align: middle;
  }

  .intercom-avatar img{
    width: 30px;
    height: 30px;
    border-radius: 50%;
  }
  .right {
  }
  .right .intercom-comment-container {
    padding: 0;
    padding: none;
    width: fit-content;
    max-width: 100%;
  }
  .right .intercom-conversation-part-metadata,
  .right .intercom-comment-container-admin-avatar{
    display: none;
  }
  .right .intercom-comment{
    color: #fff;
    background-color: #1F8CEB;
  }

</style>