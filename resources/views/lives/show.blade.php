@extends('layouts.app')

@section('content')
<div class="main-content">
	<div class="video">
		<iframe src="{{$live->m3u}}" frameborder="0" width="100%" height="200px"></iframe>
	</div>
    <live :user="{{ $user }}" :live="{{ $live }}" :viewed="{{ $viewed }}"></live>
</div>
@endsection

@section('style')
<style>
	html {
	  overflow: hidden;
	  height: 100%;
	}
	body {
		height: 100%;
		overflow: auto;

		background-image: url(/image/background-1@2x.aea5e218.png);
		background-size: 417px 417px;
		background-repeat: repeat;
		background-color: #fff;
	}
	textarea:focus
	{
		outline:none; /*or outline-color:#FFFFFF; if the first doesn't work*/
		border:1px solid #3490dc;
		-webkit-box-shadow: 0px 0px 4px 0px #3490dc;
		box-shadow: 0px 0px 4px 0px #3490dc;
	}
	#app {
		height: 100%;
   	 	overflow: hidden;
	}
	#app>nav{
		display: none;
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

	.scrollable{
	    padding-top: 1rem;
	    height: 55vh; 
	    overflow-y: scroll;
	}
	.scroll-down{
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
	.intercom-conversation-footer{
		opacity: 1;
		animation-delay: 80ms;
		animation-timing-function: cubic-bezier(.23, 1, .32, 1);
		animation-duration: .32s;
		position: fixed;
		bottom: -7px;
		left: 0;
		right: 0;
		border-radius: 0 0 6px 6px;
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
		background-image: url(/image/mobile-send@2x.5a60f4ee.png);
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
		background-color: #1F8CEB;
		transition: opacity .12s ease-in;
		border: none;
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
	.chat-avatar{
		width: 40px;
		height: 40px;
		border-radius: 50%;
	}
	#online-avatar{
		margin: 1rem 0 1rem 1rem;
	}
	.chat-profile{
		width: 50px;
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
	.statics{
		color: gray;
		font-size: 12px;
	}
	.li.right{
		padding-right: 1em;
		max-width: 80%;
	}
</style>
@endsection

