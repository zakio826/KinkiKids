<!-- ユーザー登録ページ -->

<!-- ヘッダー -->
<?php
$page_title = "アカウント作成";
$stylesheet_name = "login.css";
require_once("../include/header.php");
?>

<?php 
require($absolute_path."lib/entry_class.php");
// entryクラスのインスタンスを作成
$entry = new entry($db);

// フォームが送信されたかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entry->__construct($db);
}
?>

<main>
    <div class="content">
        <div class="frame_entry">
            <div class="wrapper1">
                <form action="entry.php" method="POST">
                    <div class="title"><h1>アカウント<ruby>作成<rt>さくせい</rt></ruby></h1></div>
                    <p><ruby>当<rt>とう</rt></ruby>サービスをご<ruby>利用<rt>りよう</rt></ruby>するために、<br><ruby>次<rt>つぎ</rt></ruby> のフォームに<ruby>必要事項<rt>ひつようじこう</rt></ruby>をご<ruby>記入<rt>きにゅう</rt></ruby>ください。</p>

                    <br>

                    <div class="scrollable-container">
                        <!-- 「FIXME」管理ユーザーがログインしている場合は表示しないようにする -->
                        <div class="form-group_entry">
                            <label for="family_name"><ruby>家族名<rt>かぞくめい</rt></ruby></label>
                            <input id="family_name" type="text" name="family_name" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['family_name']) ? htmlspecialchars($_SESSION['join']['family_name'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->familyname_error(); ?>
                        </div>
                        
                        <div class="form-group_entry">
                            <label for="username">ユーザー<ruby>名<rt>めい</rt></ruby></label>
                            <input id="username" type="text" name="username" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['username']) ? htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->username_error(); ?>
                            <p class="note"><b>※半角英数字20文字以内</b></p>
                            <p class="note"><b>※特殊文字（. - _）のみ使用可能</b></p>
                        </div>

                        <div class="form-group_entry">
                            <label for="password">パスワード</label>
                            <input id="password" type="password" name="password" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['password']) ? htmlspecialchars($_SESSION['join']['password'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->password_error(); ?>
                            <p class="note"><b>※半角英数字8文字以上</b></p>
                        </div>

                        <div class="form-group_entry">
                            <label for="last_name"><ruby>名字<rt>みょうじ</rt></ruby></label>
                            <input id="last_name" type="text" name="last_name" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['last_name']) ? htmlspecialchars($_SESSION['join']['last_name'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->firstname_error(); ?>
                        </div>

                        <div class="form-group_entry">
                            <label for="first_name"><ruby>名前<rt>なまえ</rt></ruby></label>
                            <input id="first_name" type="text" name="first_name" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['first_name']) ? htmlspecialchars($_SESSION['join']['first_name'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->lastname_error(); ?>
                        </div>

                        <div class="form-group_entry">
                            <label for="birthday"><ruby>誕生日<rt>たんじょうび</rt></ruby></label>
                            <input id="birthday" type="date" name="birthday" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['birthday']) ? htmlspecialchars($_SESSION['join']['birthday'], ENT_QUOTES) : ''; ?>">
                            <?php $entry->birthday_error(); ?>
                        </div>

                        <!-- DBの負担を減らすためプルダウンは手入力 -->
                        <div class="form-group_entry">
                            <label for="gender_id"><ruby>性別<rt>せいかく</rt></ruby></label>
                            <select name="gender_id" id="gender_id" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['gender_id']) ? htmlspecialchars($_SESSION['join']['gender_id'], ENT_QUOTES) : ''; ?>">
                                <option value="1"><ruby>女性<rt>じょせい</rt></ruby></option>
                                <option value="2"><ruby>男性<rt>だんせい</rt></ruby></option>
                                <option value="3">その<ruby>他<rt>た</rt></ruby></option>
                            </select>
                        </div>

                        <div class="form-group_entry">
                            <label for="role_id"><ruby>役割<rt>やくわり</rt></ruby></label>
                            <select name="role_id" id="role_id" class="form-control_entry"
                            value="<?php echo isset($_SESSION['join']['role_id']) ? htmlspecialchars($_SESSION['join']['role_id'], ENT_QUOTES) : ''; ?>">
                            <!-- 「FIXME」ログインされていない場合は管理者の役割しか選べないように修正する -->
                            <?php $entry->role_select(); ?>
                            </select>
                        </div>
                        
                        <div class="form-group_entry">
                            <button type="submit" class="btn btn-primary btn_margintop"><ruby>確認<rt>かくにん</rt></ruby>する</button>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</main>

<!-- フッター -->
<?php require_once("../include/footer.php"); ?>