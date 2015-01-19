<div class="remodal" data-remodal-id="modal">
	<div class="chat gen">
		<div class="title">
			<span class="contact_name button" id="cont_title">和Luxurioust聊天中</span>
		</div>

		<div id="contractlist" style="display: block;">
		</div>

		<div id="conversation" class="conversation">
			<div id="null-nouser" class="chat01_content"></div>
			<div class="time">
				<div class="time">
					<p>{{ date('Y-m-d H:m:s') }}</p>
				</div>
			</div>
		</div>
		<div class="message">
			<input id="talkInputId" type="text" name="imessage" value="" placeholder="输入消息……"/>
			<div id="i_box">
				<a href="javascript:showEmotionDialog()"><i class="fa fa-smile-o"></i></a>
				<a href="javascript:showSendPic()"><i class="fa fa-picture-o"></i></a>
				<a href="javascript:showSendAudio()"><i class="fa fa-file-audio-o"></i></a>
			</div>
			<div id="wl_faces_box" class="wl_faces_box">
				<div class="wl_faces_content">
					<div class="title">
						<ul>
							<li class="title_name">常用表情</li>
							<li class="wl_faces_close"><span onclick='turnoffFaces_box()'>&nbsp;</span></li>
						</ul>
					</div>
					<div id="wl_faces_main" class="wl_faces_main">
						<ul id="emotionUL">
						</ul>
					</div>
				</div>
				<div class="wlf_icon"></div>
			</div>
		</div>
	</div>

	<div id="fileModal">
		<div class="modal-header">
			<button type="button" class="close" onclick="hiddenSendPic()">&times;</button>
			<h3>文件选择框</h3>
		</div>
		<div class="modal-body">
			<input type='file' id="fileInput" /> <input type='hidden'
				id="sendfiletype" />
			<div id="send-file-warning"></div>
		</div>
		<div class="modal-footer">
			<button id="fileSend" class="btn-primary" onclick="sendFile()">发送</button>
			<button id="cancelfileSend" class="btn" onclick="hiddenSendPic()">取消</button>
		</div>
	</div>
</div>