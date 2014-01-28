<div class="postImages">
        <ul>
    
{foreach $list as $key => $value}
        <li id="picture_{$value.contentID}">
            <div class="imageItem" class="" data-picture="{$value.picture}">
                <a href="javascript:;" class="editPictureThumbs" data-picture="{$value.picture}" data-id="{$value.contentID}"><img src="/content/photo/200x200/{$value.picture}"  width="140" /></a>
                <a href="javascript:;" class="addPictureToText" data-picture="{$value.picture}">Вставить в пост</a>
                <a href="javascript:;" class="addPictureToPoster" data-picture="{$value.picture}">Сделать постером</a>
                <a href="javascript:;" class="removePicture" data-picture="{$value.picture}" data-id="{$value.contentID}">[X]</a>
            </div>
        </li>
    
{/foreach}
    </ul>
    </div>
