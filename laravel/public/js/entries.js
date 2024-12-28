document.addEventListener('DOMContentLoaded', function() {
	//  *------------------------------------------*
	//  index 同意ボタンと送信ボタンの連動
	//  *------------------------------------------*
    const consent = document.querySelector('input[name="consent"]');
    const mailSubmit = document.getElementById('submit');
    if(consent !== null) {
        mailSubmit.disabled = true;
        consent.addEventListener('change', function() {
            if(this.checked) {
                mailSubmit.disabled = false;
            } else {
                mailSubmit.disabled = true;
            }
        });
    }

	//  *------------------------------------------*
	// send 仮メール送信の時のボタン
	//  *------------------------------------------*
    const address = document.getElementById('entries-address');
    if(address != null) {
        const mailBtn = document.getElementById('entries-mail-btn');
        const adDomain = address.textContent.split('@')[1];
        if(adDomain == 'gmail.com') {
            mailBtn.textContent = 'Gmailを確認する';
            mailBtn.href = 'https://accounts.google.com/ServiceLogin?service=mail&passive=true&rm=false&continue=https://mail.google.com/mail/?tab%3Dwm&scc=1&ltmpl=default&ltmplcache=2&emr=1';
            mailBtn.setAttribute('target', '_blank');
            mailBtn.setAttribute('rel', 'noopener noreferrer');
        }
    }

	//  *------------------------------------------*
	//  create 生年月日
	//  *------------------------------------------*
    const userBirthday = document.getElementById('birthday');
    if(userBirthday != null) {
        let userBtY= document.querySelector('.js-birthday-year');
        let userBtM = document.querySelector('.js-birthday-month');
        let userBtD = document.querySelector('.js-birthday-day');
        const minor = document.getElementById('minor-consent');
        const minorCheckbox = minor.closest('.checkbox');
        const today = new Date();
        const year = today.getFullYear();
        const by= userBtY.getAttribute("val");
        const bm = userBtM.getAttribute("val");
        const bd = userBtD.getAttribute("val");
        let birthday = {};
        minorCheckbox.classList.add('hide');
        /**
         * selectのoptionタグを生成するための関数
         * @param {Element} elem 変更したいselectの要素
         * @param {Number} val 表示される文字と値の数値
         */

        function createOptionForElements(elem, val, selected = '') {
            let option = document.createElement('option');
            option.text = val;
            option.value = val;
            elem.appendChild(option);
            if(val == false) {
                option.removeAttribute('value');
                option.disabled = true;
                if(selected ==''){
                    option.selected = true;
                }
            }
            if(selected!='' && val == selected){
                option.selected = true;
            }
        }

        Promise.resolve()
        .then(function(){
          return new Promise(function (resolve, reject) {
            //年の生成
            for(let i = year; i >= (year - 120); i--) {
                createOptionForElements(userBtY, i, by);
                if(i == (year - 35)) {
                    createOptionForElements(userBtY, '', by);
                }
            }
            //月の生成
            createOptionForElements(userBtM, '');
            for(let i = 1; i <= 12; i++) {
                createOptionForElements(userBtM, i, bm);
            }
            //日の生成
            createOptionForElements(userBtD, '');
            for(let i = 1; i <= 31; i++) {
                createOptionForElements(userBtD, i, bd);
            }
            resolve();
          });
        })
        .then(function(){
          return new Promise(function (resolve, reject) {
            setDay();
            getAge(birthday);
            resolve();
          });
        });

        /**
        /**
         * 日付を変更する関数
         */
        function changeTheDay() {
            //日付の要素を削除
            userBtD.innerHTML = '';
            //選択された年月の最終日を計算
            let lastDayOfTheMonth = new Date(userBtY.value, userBtM.value, 0).getDate();
            //選択された年月の日付を生成
            createOptionForElements(userBtD, '');
            for(let i = 1; i <= lastDayOfTheMonth; i++) {
                createOptionForElements(userBtD, i);
            }
            birthday = {
                year: userBtY.value,
                month: userBtM.value,
                date: userBtD.value
            };
        }

        function setDay(){
            birthday = {
                year: userBtY.value,
                month: userBtM.value,
                date: userBtD.value
            };
        }

        function getAge(birthday){
            var thisYearsBirthday = new Date(today.getFullYear(), birthday.month-1, birthday.date);
            var age = today.getFullYear() - birthday.year;
            if(today < thisYearsBirthday){
                age--;
            }
            if(age < 18) {
                minorCheckbox.classList.remove('hide');
                minor.required = true;
            } else {
                minorCheckbox.classList.add('hide');
                minor.required = false;
            }
            return age;
        }
        userBtY.addEventListener('change', function() {
            changeTheDay();
            getAge(birthday);
        });
        userBtM.addEventListener('change', function() {
            changeTheDay();
            getAge(birthday);
        });
        userBtD.addEventListener('change', function() {
            setDay();
            getAge(birthday);
        });
    }

	//  *------------------------------------------*
	//  create その他自由記述
	//  *------------------------------------------*
    const CreOthInput = document.querySelectorAll('.js-other-input');
    if (CreOthInput.length) {
		CreOthInput.forEach(function (e) {
            e.addEventListener('click', function() {
                e.previousElementSibling.querySelector('input').checked = true;
            });
        });
    }

	//  *------------------------------------------*
	//  create バリデーション
	//  *------------------------------------------*
    const entryForm = document.querySelector('.js-entry-form');
    if (entryForm) {
        const submit = document.getElementById('submit');
        const errorMessages = document.querySelectorAll('.js-error-message');
        let errorMessageMT = 0;
        errorMessages.forEach(function (e) {
            e.style.marginTop = 0;
            errorMessageMT = Number(window.getComputedStyle(e, null).getPropertyValue('margin-top').replace(/[^0-9.]/g, ''));
            return errorMessageMT + 'px';
        });
        entryForm.querySelectorAll('input:not(.js-validation-none)').forEach(function (e) {
            e.addEventListener('change', function() {
                e.setCustomValidity('');
                formError();
            });
            submit.addEventListener('click', function() {
                formError();
            });
            function formError() {
                const errorMessage = e.parentElement.querySelector('.js-error-message');
                const pDd = e.closest('dd');
                const pDt = pDd != null ? pDd.previousElementSibling : null;

                const eId = e.getAttribute('id');
                const confE = document.getElementById(eId + '_confirmation');
                const confEErrorMessage = confE != null ? confE.parentElement.querySelector('.js-error-message') : null;
                const confPDd = confE != null ? confE.closest('dd') : null;
                const confPDt = confPDd != null ? confPDd.previousElementSibling : null;
                const confEJudge = eId != null && eId.indexOf('confirmation') > -1;

                const confTargetId = confEJudge ? eId.replace('_confirmation', '') : null;
                const confTarget = document.getElementById(confTargetId);
                const confTargetLabel = confEJudge ? document.querySelector('label[for='+ confTargetId +']').textContent : null;

                let vMessage = '';
                new Promise((resolve, reject) => {
                    if(e.validationMessage != false) {
                        vMessage = e.validationMessage;
                        reject();
                    } else {
                        resolve();
                    }
                }).then(() => {
                    if(confEJudge) {
                        if(e.value !== confTarget.value) {
                            vMessage = confTargetLabel + 'が一致しません';
                            e.setCustomValidity(vMessage);
                            throw new Error('Values do not match');
                        } else {
                            e.setCustomValidity('');
                        }
                    } else if(eId != null && confE != null) {
                        if(confE.value.length > 0 && e.value != confE.value) {
                            vMessage = document.querySelector('label[for='+ eId +']').textContent + 'が一致しません';
                            confE.setCustomValidity(vMessage);
                            confEErrorMessage.innerHTML = '<span class="icon-attention"></span>' + vMessage;
                            confEErrorMessage.style.marginTop = errorMessageMT + 'px';
                            if(confE.classList.contains('is-error') != true) {
                                confE.classList.add('is-error');
                            };
                            if(confPDt != null) {
                                confPDt.classList.add('form-error');
                            }
                        } else if(confE.value.length > 0 && e.value == confE.value) {
                            confEErrorMessage.innerHTML = '';
                            confEErrorMessage.style.marginTop = 0;
                            confE.setCustomValidity('');
                            if(confPDt != null) {
                                confPDt.classList.remove('form-error');
                            }
                            e.classList.remove('is-error');
                        }
                    }
                }).then(() => {
                    e.classList.remove('is-error');
                    if(errorMessage != null) {
                        errorMessage.innerHTML = '';
                        errorMessage.style.marginTop = 0
                        if(e.customError == true) {
                            e.setCustomValidity('');
                        }
                    }
                    if(pDd.querySelector('.is-error') == null) {
                        if(pDt != null) {
                            pDt.classList.remove('form-error');
                        } else {
                            e.parentElement.classList.remove('form-error');
                        }
                    }
                }).catch(() => {
                    if(e.classList.contains('is-error') != true) {
                        e.classList.add('is-error');
                    };
                    if(errorMessage != null) {
                        errorMessage.innerHTML = '<span class="icon-attention"></span>' + vMessage;
                        errorMessage.style.marginTop = errorMessageMT + 'px';
                    }
                    if(pDt != null && pDt.classList.contains('form-error') != true) {
                        pDt.classList.add('form-error');
                    } else if(pDt == null && e.classList.contains('form-error') != true) {
                        e.parentElement.classList.add('form-error');
                    }
                });
            }
        });

        if(entryForm.querySelectorAll('select:not(.js-validation-none)') != null) {
            entryForm.querySelectorAll('select:required:not(.js-validation-none)').forEach(function (e) {
                e.addEventListener('change', function() {
                    e.setCustomValidity('');
                    formError();
                });
                submit.addEventListener('click', function() {
                    formError();
                });
                function formError() {
                    const eP = e.closest('.selects');
                    const pDd = e.closest('dd');
                    const errorMessage = pDd.querySelector(pDd.tagName + ' > .js-error-message');
                    const pDt = pDd != null ? pDd.previousElementSibling : null;

                    let vMessage = '';
                    let eSelect = e.options[e.selectedIndex].value;

                    new Promise((resolve, reject) => {
                        if(eSelect == false) {
                            vMessage = 'リスト内の項目を選択してください。';
                            e.setCustomValidity(vMessage);
                            reject();
                        } else {
                            resolve();
                        }
                    }).then(() => {
                        e.parentElement.classList.remove('is-error');
                        if(eP.querySelector('.is-error') == null) {
                            if(errorMessage != null) {
                                errorMessage.innerHTML = '';
                                errorMessage.style.marginTop = 0
                                if(e.customError == true) {
                                    e.setCustomValidity('');
                                }
                            }
                        }
                        if(pDd.querySelector('.is-error') == null) {
                            if(pDt != null) {
                                pDt.classList.remove('form-error');
                            }
                        }
                    }).catch(() => {
                        if(e.parentElement.classList.contains('is-error') != true) {
                            e.parentElement.classList.add('is-error');
                        };
                        if(errorMessage != null && errorMessage.innerHTML.length == 0) {
                            errorMessage.innerHTML = '<span class="icon-attention"></span>' + vMessage;
                            errorMessage.style.marginTop = errorMessageMT + 'px';
                        }
                        if(pDt != null && pDt.classList.contains('form-error') != true) {
                            pDt.classList.add('form-error');
                        } else if(pDt == null && e.classList.contains('form-error') != true) {
                            e.parentElement.classList.add('form-error');
                        }
                    });
                }
            });
        }
    }

	//  *------------------------------------------*
	//  create 紹介コード
	//  *------------------------------------------*
    const invitation = document.getElementById('invitation');
    if(invitation != null) {
        const invCode = document.querySelector('.js-invitation_code');
        const invCodeMT = Number(window.getComputedStyle(invCode, null).getPropertyValue('margin-top').replace(/[^0-9.]/g, ''));
        const invCodeErr = invCode.querySelector('.js-error-message');
        let invCodeErrH = 0;
        const invCodeErrMT = Number(window.getComputedStyle(invCodeErr, null).getPropertyValue('margin-top').replace(/[^0-9.]/g, ''));
        const invCodeH = invCode.offsetHeight - invCodeErrMT;
        const invLabel = invitation.nextElementSibling;
        const invCodeInput = invCode.querySelector('input')
        const invLabelTxt = invitation.nextElementSibling.innerText;
        invCode.style.display = 'none';
        invCode.style.height = 0;
        invCode.style.marginTop = 0;
        invCodeErr.style.marginTop = 0;
        invitation.addEventListener('click', function() {
            if(invitation.checked == true) {
                toggleAccordion(invCode);
            } else {
                toggleAccordion(invCode);
            }
        });

        const invitation_code = document.getElementById('invitation_code').value;
        if(invitation_code){console.log(invitation_code);
            toggleAccordion(invCode);
        }
        
        function toggleAccordion(target) {
            invCodeInput.addEventListener('change', function() {
                if(target.closest('dd').previousElementSibling.classList.contains('form-error') == true && invCode.parentElement.querySelector('.js-error-message').innerHTML.length > 0) {
                    invCodeErrH = invCodeErr.offsetHeight;
                    invCode.style.height = invCodeH + invCodeErrH + invCodeErrMT + 'px';
                } else {
                    invCodeErrH = 0;
                    invCode.style.height = invCodeH + 'px';
                }
            });
            if (target.classList.contains('is-active')) {
                invLabel.innerText = invLabelTxt;
                invCodeInput.value = '';
                new Promise((resolve) => {
                    target.classList.remove('is-active');
                    invCodeInput.removeAttribute('class');
                    invCode.style.height = 0;
                    invCode.style.marginTop = 0;
                    resolve();
                }).then(() => {
                    setTimeout(() => {
                        invCode.style.display = 'none';
                    }, 310);
                });
            } else {
                invLabel.innerText = '紹介コードの入力を取り消す';
                new Promise((resolve) => {
                    invCode.style.display = 'block';
                    invCode.parentElement.querySelector('.js-error-message').innerHTML = '';
                    resolve();
                }).then(() => {
                    setTimeout(() => {
                        target.classList.add('is-active');
                        invCode.style.height = invCodeH + 'px';
                        invCode.style.marginTop = invCodeMT + 'px';
                    }, 10);
                });
            }
        }
    }

	//  *------------------------------------------*
	//   confirm_tel 発信認証電話番号QR
	//  *------------------------------------------*
    const telNumE = document.querySelector('.js-telnum');
    if(telNumE != null) {
        const telNum = telNumE.textContent;
        const telNumQr = document.querySelector('.js-telnum-qr');
        new QRCode(telNumQr, {
            text:  telNum,
            colorDark : "#333",
            colorLight : "transparent",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

	//  *------------------------------------------*
	//   urlHash スムーススクロール
	//  *------------------------------------------*
	const headerHeight = document.querySelector('.gmoGroupHeader') !== null ? document.querySelector('.gmoGroupHeader').offsetHeight : 0;
	let pageHash = window.location.hash;
    if(pageHash.length) {
        window.onload = function () {
            let target = document.querySelector(pageHash);
            const targetPos = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 16;
            window.scrollTo({
                top: targetPos,
                behavior: 'smooth'
            });
        }
    }

    //  *------------------------------------------*
	//   電話番号認証ステータスチェックポーリング
	//  *------------------------------------------*
    if(document.getElementById('ost-token-tel')){
        const ost_token_tel = document.getElementById('ost-token-tel').value;
        const ost_token = document.getElementById('ost-token').value;
        if (ost_token_tel != '' && ost_token_tel != null && ost_token_tel !== undefined && ost_token != '' && ost_token != null && ost_token !== undefined){
            var POLLLING_INVERVAL_TIME_IN_MILLIS =  5000;//5s
            (function polling() {
            check_tel_auth();
            window.setTimeout(polling, POLLLING_INVERVAL_TIME_IN_MILLIS);
            }());

            function check_tel_auth() {
                $.ajax({
                    type: 'get',
                    url: '/api/check_auth_tel/'+ost_token+'/'+ost_token_tel+'/',
                    dataType: "json",
                    data: {
                    },
                    success: function(response) {console.log(response);
                        var check = response.result;
                        if (check === true) {
                            document.getElementById("entries-auth-tel").click();
                        }

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                    }
                });
            }
        }
    }
});
