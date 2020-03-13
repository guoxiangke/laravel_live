<template>
   <div class="liveComponent">
    <div class="container">
        <div class="statics">
          <div class="button is-info is-outlined is-small"><i class="fas fa-users"></i>{{ users.length }}</div>
          <div class="button is-info is-small"><i class="fas fa-eye"></i>{{ viewed }}</div>
        </div>
    </div>

    <div id="tabs-with-content">
      <div class="tabs is-centered">
        <ul class="tab-title">
        <li><a>互动消息</a></li>
        <li><a>图文介绍</a></li>
        <li><a>当前在线</a></li>
        </ul>
      </div>
      <div class="tab-contents">
        <section class="tab-content">
          <div class="scroll-content scrollable" id="message-scroll">
              <a href="#" class="scroll-down is-hidden"></a>
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
                <div class="intercom-conversation-part-metadata">{{ message.user.socials[0]?message.user.socials[0].name: message.user.name }} &nbsp;&nbsp; {{ message.created_at.substr(5,11) }}</div>
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
        </section>

        <section class="tab-content">
          <div class="scroll-content scrollable">
            <a href="#" class="scroll-down is-hidden"></a>
            <h1 class="title is-4">{{live.title}}</h1>
            <p class="description">
              {{live.description}}
            </p>
          </div>
        </section>

        <section class="tab-content">
          <div class="scroll-content scrollable">
            <a href="#" class="scroll-down is-hidden"></a>
            <span class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing...</span>
            <div class="columns is-mobile is-multiline" id="online-avatar">
              <div class="column is-narrow" v-for="(user, index) in users" :key="index">
                <div class="chat-profile">
                  <img class="chat-avatar" v-if="user.socials" :src="user.socials[0]?user.socials[0].avatar:'/image/default.avatar.png'"  alt="">
                  <p class="size-ex-small">{{ user.name }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

   </div>
</template>
<style>
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
            $('#setIframeHeight').click(function(){
              let obj = document.getElementById('iframe');
              obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
              $(this).hide();
            })
            $('#intercom-textarea').blur(function(){
              $('.intercom-conversation-footer').css('position','fixed');
            });
            $('#intercom-textarea').focus(function(){
              $('.intercom-conversation-footer').css('position','absolute');
            });

            $('.scroll-down').click(function(){
              let scroll = $(this).parents(".scroll-content");
              scroll.animate({ scrollTop: scroll.prop('scrollHeight') }, 1000);
              $(this).toggleClass('is-hidden');
            });

            $( ".scroll-content" ).scroll(function() {
              let h1 = $(this).prop('scrollHeight');
              let h2 = $(this).prop('scrollTop');
              if(h1>400){
                if(h2>200){
                  $(this).children('.scroll-down').removeClass('is-hidden');
                }
                if(h1-h2<500){
                    $(this).children('.scroll-down').addClass('is-hidden');
                }
              }
            });
            // tabs begin!
            let tabsWithContent = (function () {
            let tabs = document.querySelectorAll('.tabs li');
            let tabsContent = document.querySelectorAll('.tab-content');

            let deactvateAllTabs = function () {
              tabs.forEach(function (tab) {
                tab.classList.remove('is-active');
              });
            };

            let hideTabsContent = function () {
              tabsContent.forEach(function (tabContent) {
                tabContent.classList.remove('is-active');
              });
            };

            let activateTabsContent = function (tab) {
              tabsContent[getIndex(tab)].classList.add('is-active');
            };

            let getIndex = function (el) {
              return [...el.parentElement.children].indexOf(el);
            };

            tabs.forEach(function (tab) {
              tab.addEventListener('click', function () {
                deactvateAllTabs();
                hideTabsContent();
                tab.classList.add('is-active');
                activateTabsContent(tab);
              });
            })

            tabs[0].click();
          })();
          // tabs end
        },
        created() {
            // this.fetchMessages();
            Echo.join(this.room)
                .here(user => {
                    this.users = user;
                })
                .joining(user => {
                    this.users.push(user);
                    this.viewed++;
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('MessageSent', (event) => {
                    this.messages.push(event.message);

                    let scroll = $("#message-scroll");
                    let h1 = scroll.prop('scrollHeight');
                    let h2 = scroll.prop('scrollTop');
                    if(h1 - h2 < 1000){
                      scroll.animate({ scrollTop: scroll.prop('scrollHeight') }, 1000);
                      $('#message-scroll.scroll-down').addClass('is-hidden');
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
              return user_id == this.user.id?'right is-pulled-right':'';
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

                let scroll = $("#message-scroll");
                let h1 = scroll.prop('scrollHeight');
                let h2 = scroll.prop('scrollTop');
                if(h1 - h2 < 1000){
                  scroll.animate({ scrollTop: scroll.prop('scrollHeight') }, 1000);
                  $('#message-scroll.scroll-down').addClass('is-hidden');
                }
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