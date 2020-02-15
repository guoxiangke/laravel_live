<template>
   <div class="liveComponent">
      <div class="video text-center">
        <iframe :src="'https://livelss.cdn.bcebos.com/index.html?stream=classroom&vid=' + live.vid + '&preset=L3&live=' + live.live" frameborder="0"></iframe>
        <div>
          <span class="statics">在线人数：<strong>{{ users.length }}</strong></span>
          &nbsp;&nbsp;
          <span class="statics">浏览次数：<strong>{{ viewed }}</strong></span>
        </div>
        <a href="#" id="scroll-down">ScrollDown</a>
      </div>

      <div class="tabs-wrapper">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">在线消息</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">图文介绍</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">在线用户</a>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">

          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
             
            <div class="tab-wrapper">
                <div class="list-unstyled scroll-list" id="scroll">
                    <div class="li" v-bind:class="checkCodes(message.user_id)" v-for="(message, index) in messages" :key="index" >
                      <div class="intercom-comment-container" >
                        <div class="intercom-comment-container-admin-avatar">
                          <div class="intercom-avatar">
                            <img :src="message.user.socials[0]?message.user.socials[0].avatar:'/image/default.avatar.png'" alt="">
                          </div>
                        </div>
                        <div class="intercom-comment">
                          <div class="intercom-block-paragraph">
                            {{ message.message }}
                          </div>
                        </div>
                      </div>
                      <div class="intercom-conversation-part-metadata">{{ message.user.socials[0]?message.user.socials[0].name: message.user.name }}</div>
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
        
          
        <div class="intercom-conversation-footer">
            <textarea
                id="intercom-textarea"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
                v-model="newMessage"
                type="textarea"
                name="message"
                placeholder="发布消息..."></textarea>
            <button class="intercom-composer-send-button" @click="sendMessage"></button>
        </div>

   </div>
</template>
<style>
  #scroll-down{
    -webkit-transform: rotate(180deg);
    -moz-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    -o-transform: rotate(180deg);
    transform: rotate(180deg);
    width:40px;
    height:40px;
    opacity:0.3;
    position:fixed;
    top:270px;
    right:1em;
    z-index: 10001;
    text-indent:-9999px;
    background: url('/image/icon_top.png') no-repeat;
}
</style>
<script>
    export default {
        props:['user', 'live', 'viewed'],
        data() {
            return {
                messages: this.live.messages,
                newMessage: '',
                users:[],
                activeUser: false,
                typingTimer: false,
                room: 'lives.' + this.live.id,
            }
        },
        mounted: function () {
            $('#intercom-textarea').blur(function(){
              $('.intercom-conversation-footer').css('position','fixed');
            });
            $('#intercom-textarea').focus(function(){
              $('.intercom-conversation-footer').css('position','absolute');
            });

            $('#scroll-down').click(function(){
              let scroll = $("#scroll");
              scroll.animate({ scrollTop: scroll.prop('scrollHeight') }, 1000);
              $(this).toggleClass('d-none');
            });

            $( "#scroll" ).scroll(function() {
              let h1 = $(this).prop('scrollHeight');
              let h2 = $(this).prop('scrollTop');
              if(h1 - h2 <= 1000){
                $('#scroll-down').addClass('d-none');
              }else{
                $('#scroll-down').removeClass('d-none');
              }
            });
        },
        created() {
            // this.fetchMessages();
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

                    let scroll = $("#scroll");
                    let h1 = scroll.prop('scrollHeight');
                    let h2 = scroll.prop('scrollTop');
                    if(h1 - h2 < 1000){
                      scroll.animate({ scrollTop: scroll.prop('scrollHeight') }, 1000);
                      $('#scroll-down').addClass('d-none');
                    }
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
            // fetchMessages() {
            //     axios.get('/messages/'+this.live.id).then(response => {
            //         this.messages = response.data;
            //     })
            // },
            sendMessage() {
                this.newMessage = this.newMessage.replace(/(\r\n|\n|\r)/gm, "").trim();
                if(this.newMessage.length == 0) {
                  $('#intercom-textarea').focus().attr('placeholder','请输入消息！');
                  return;
                }
                this.messages.push({
                    user: this.user,
                    user_id: this.user.id,
                    live: this.live,
                    message: this.newMessage
                });

                axios.post('/live/message/'+this.live.id, {message: this.newMessage});
                this.newMessage = '';
            },
            sendTypingEvent() {
              console.log('whisper-typing');
                // Echo.join(this.room).whisper('typing', this.user);
            }
        }
    }
</script>