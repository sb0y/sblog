<div class="row newMessageBlock popupMailForm">
	<div class="wrap">
		<form id="mailWriteForm" action="{$urlBase}user/mail/write" method="POST">
			<div class="credentials">
				<img src="{$urlBase}resources/images/no-avatar-small.png" id="avatarHolder" class="avatar" />
				<div class="fieldSet">
					<p>
						<div class="dropListField">
							<label for="fromMail">Получатель</label>
							<div class="selectorContainer">
								<table id="pseudoHolder" class="pseudoInputDiv{checkFormError field="receivers" space=true}"><body><tr><td>
									<span id="selInputHolder" class="selectedItems"></span><input autocomplete="off" id="fromMail" class="pseudoInput mailField dropDownField" />
								</tbody></td></tr></table>
								<div class="dropList userList">
									<ul></ul>
								</div>
							</div>
						</div>
					</p>
					<p><label for="subjectMail">Тема</label>
					<input id="subjectMail" class="mailField" name="subject" value="" /></p>
				</div>
			</div>
			<div class="input">
				<textarea id="textBody" name="body"{checkFormError field="body" class=true space=true}></textarea>
				<div class="mailPanel">
					<button name="sendMail" type="submit" value="sendMail" class="button">Отправить</button>
					{*<a class="attach">Вложить</a>*}
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="{$urlBase}resources/js/writeMail.js"></script>