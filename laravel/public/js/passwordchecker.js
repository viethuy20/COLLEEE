/*
  ライブラリ名：
     Password Checker

  バージョン：
     1.0.1

  ライセンス：
     http://www.websec-room.com/license

  パスワード強度判定
    1:弱い 2:やや弱い 3:普通 4:やや強い 5:強い
*/
const pw = document.getElementById('password');
if(pw != null) {
    const pwLv = document.getElementById('js-password-level');
    const pwLvTxt = document.getElementById('js-password-level-txt');
    const pwLvList = ['notyet', 'veryweak', 'weak', 'good', 'strong', 'verystrong'];
    let level = getPasswordLevel(pw.value);
    pwLv.setAttribute('class', 'bar ' + pwLvList[level]);
    pwLvTxt.setAttribute('class', 'txt ' + pwLvList[level]);

    pw.addEventListener('input', function(){
        level = getPasswordLevel(pw.value);
        pwLv.setAttribute('class', 'bar ' + pwLvList[level]);
        pwLvTxt.setAttribute('class', 'txt ' + pwLvList[level]);
    })
    function getPasswordLevel(password) {
        let level = 0;
        let pattern = 0;
        var hasLower = false;
        var hasUpper = false;
        var hasCharacter = false;
        var hasNumber = false;
        if (password.length < 1) {
            return level;
        }
        for (i = 0; i < password.length; i++) {
            const ascii = password.charCodeAt(i);
            //アルファベット小文字チェック
            if ((ascii >= 97) && (ascii <= 122)) {
                hasLower = true;
            }
            //アルファベット大文字チェック
            if ((ascii >= 65) && (ascii <= 90)) {
                hasUpper = true;
            }
            //数値チェック
            if ((ascii >= 48) && (ascii <= 57)) {
                hasNumber = true;
            }
            //!#$%&+-.<=>?@^_~
            if ((ascii == 33) ||
            ((ascii >= 35) && (ascii <= 38)) ||
            (ascii == 43) || (ascii == 45) || (ascii == 46) ||
            ((ascii >= 60) && (ascii <= 64)) ||
            (ascii == 94) || (ascii == 95) ||
            (ascii == 126)) {
                hasCharacter = true;
            }
        }
        if (hasLower) {pattern++;}
        if (hasUpper) {pattern++;}
        if (hasNumber) {pattern++;}
        if (hasCharacter) {pattern++;}
        //パスワードレベル判定
        //辞書に登録されている文字チェック
        const dictionary = ['password','qwerty','abc','admin','root','123'];
        for (i = 0; i < dictionary.length; i++) {
            if (password.indexOf(dictionary[i]) != -1) {
                level = 1;
                return level;
            }
        }

        //数値のみパスワードチェック
        if (password.match(/^[0-9]+$/)) {
            level = 1;
            return level;
        }
        if (password.length < 8) {
            level = 1;
        }
        if ((password.length >= 8) && (password.length < 14)) {
            level = 2;
        }
        if ((password.length >= 8) && (password.length < 14) && (pattern >= 2)) {
            level = 3;
        }
        if ((password.length >= 8) && (password.length < 14) && (pattern >= 3)) {
            level = 4;
        }
        if ((password.length >= 14) && (pattern < 3)) {
            level = 3;
        }
        if ((password.length >= 14) && (pattern >= 3)) {
            level = 5;
        }
        return level;
    }
}
