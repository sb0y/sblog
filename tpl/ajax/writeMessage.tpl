<div class="newMessageBlock popupMailForm">
	<form class="form-horizontal" role="form" id="mailWriteForm" action="{$urlBase}user/mail/write" method="POST">
		<div class="col-md-4 text-center">
			<h4 class="avatar">Фото</h4>
			<img style="width:128px;" src="{$urlBase}resources/images/no-avatar-small.png" id="avatarHolder" class="profileAvatar img-responsive img-thumbnail" />
		</div>
		<div class="col-md-8">
			<div class="form-group">
				<div class="dropListField">
					<label for="fromMail">Получатель</label>
					<div class="selectorContainer">
						<table id="pseudoHolder" class="pseudoInputDiv form-control{checkFormError field="receivers" space=true}"><tbody><tr><td>
							<span id="selInputHolder" class="selectedItems"></span><input autocomplete="off" id="fromMail" class="pseudoInput mailField dropDownField" />
							</td></tr></tbody></table>
							<div class="dropList userList">
								<ul class="list-group"></ul>
							</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="subjectMail">Тема</label>
				<input class="form-control" id="subjectMail" class="mailField" name="subject" value="" />
			</div>
			<div class="form-group">
				<textarea rows="10" class="form-control" id="textBody" name="body"{checkFormError field="body" class=true space=true}></textarea>
			</div>

			<div class="form-group">
				<button onclick="writeMail.sendForm()" type="button" id="sendButton" class="btn btn-primary">Отправить</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="{$urlBase}resources/js/writeMail.js"></script>