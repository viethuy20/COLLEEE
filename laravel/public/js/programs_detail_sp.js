$(function () {
	//  *------------------------------------------*
	//  お気に入りボタン
	//  *------------------------------------------*
	$(".js-fav").click(function () {
		$(this).toggleClass("is-active");
		$(this)
			.addClass("is-tooltip")
			.promise()
			.then(function () {
				setTimeout(function () {
					$(".js-fav").removeClass("is-tooltip");
				}, 1001);
			});
	});
	//  *------------------------------------------*
	//  参考になったボタン
	//  *------------------------------------------*
	const likeElements = Array.from(document.getElementsByClassName("js-like"));
	if (likeElements.length) {
		likeElements.forEach(function (e) {
			const count = e.querySelector(".js-like-count");
			e.addEventListener("click", function () {
				e.classList.toggle("is-active");
				if (e.classList.contains("is-active") == 1) {
					count.textContent = Number(count.textContent) + 1;
					e.setAttribute("style", "pointer-events:none;cursor:default;");
				} else {
					count.textContent = Number(count.textContent) - 1;
				}
				return;
			});
		});
	}

	//  *------------------------------------------*
	//  テキストエリアの高さを隠す
	//  *------------------------------------------*
	const txtLimit = Array.from(document.getElementsByClassName("js-txt-limit"));
	if (txtLimit.length) {
		txtLimit.forEach(function (e) {
			const eHeight = e.offsetHeight;
			const eStyle = window.getComputedStyle(e, null);
			const eLineHeight = Math.ceil(eStyle.getPropertyValue("line-height").replace(/[^0-9.]/g, ""));
			const btnMoreHTML = '<a href="javascript:void(0);" class="js-more-btn textlink">…続きを読む</a>';
			const row = 3;
			const hLimit = eLineHeight * row;
			if (eHeight > hLimit) {
				e.style.height = hLimit + "px";
				if (e.getElementsByClassName("js-more-btn").length == 0) {
					e.insertAdjacentHTML("beforeend", btnMoreHTML);
				}
			}
		});
		const btnMore = Array.from(document.getElementsByClassName("js-more-btn"));
		if (btnMore.length) {
			btnMore.forEach(function (e) {
				e.addEventListener("click", function () {
					if (e.closest(".js-txt-limit")) {
						e.closest(".js-txt-limit").style.height = e.closest(".js-txt-limit").scrollHeight + "px";
						new Promise((resolve) => {
							e.classList.add("is-hidden");
							resolve();
						}).then(() => {
							setTimeout(() => {
								e.remove();
							}, 160);
						});
					}
				});
			});
		}
	}

	//  *------------------------------------------*
	//  口コミの星の数
	//  *------------------------------------------*
	const starCountElements = document.querySelectorAll(".js-star-count");
	if (starCountElements.length) {
		starCountElements.forEach(function (e) {
			const starCount = e ? Math.floor(Number(e.textContent)) : -1;
			if (starCount > -1) {
				const starList = e.previousElementSibling.classList.contains("js-star") ? e.previousElementSibling : null;
				const star = starList.getElementsByTagName("li");
				for (var i = 0; i < starCount; i++) {
					star[i].classList.add("color");
				}
			}
		});
	}

	//  *------------------------------------------*
	//  口コミをもっと見るボタン
	//  *------------------------------------------*
	const review = document.querySelector(".js-review");
	if (review !== null) {
		const reviewCount = review.childElementCount;
		const show = 4; //最初に表示する件数
		const num = 4; //clickごとに表示したい件数
		const btnMore = document.querySelector(".js-review-more");
		const btnMoreWrap = btnMore.closest(".btn__wrap");
		for (var i = show; i < reviewCount; i++) {
			review.children[i].classList.add("is-hidden");
		}
		if (reviewCount > show) {
			btnMore.addEventListener("click", function () {
				let isHidden = review.querySelectorAll(".is-hidden");
				function showReviewItem(target) {
					new Promise((resolve) => {
						target.style.height = target.scrollHeight + "px";
						resolve(target);
					}).then((e) => {
						setTimeout(() => {
							e.style.height = "";
						}, 300);
						e.classList.remove("is-hidden");
					});
				}
				new Promise((resolve) => {
					if (isHidden.length >= num) {
						for (var i = 0; i < num; i++) {
							showReviewItem(isHidden[i]);
						}
					} else {
						for (var i = 0; i < isHidden.length; i++) {
							showReviewItem(isHidden[i]);
						}
					}
					resolve();
				}).then(() => {
					isHidden = review.querySelectorAll(".is-hidden");
					if (isHidden.length == 0) {
						new Promise((resolve) => {
							btnMoreWrap.classList.add("is-hidden");
							resolve();
						}).then(() => {
							setTimeout(() => {
								btnMoreWrap.remove();
							}, 160);
						});
					}
				});
			});
		} else {
			btnMoreWrap.remove();
		}
	}

	//  *------------------------------------------*
	//  スムーススクロール
	//  *------------------------------------------*
	const headerHeight = document.querySelector(".gmoGroupHeader") !== null ? document.querySelector(".gmoGroupHeader").offsetHeight : 0;
	const scrollBtn = document.querySelectorAll(".js-scroll");
	if (scrollBtn.length) {
		scrollBtn.forEach(function (e) {
			e.addEventListener("click", function () {
				const position = e.dataset.scroll;
				const target = document.querySelector('.js-scroll-target[data-scroll="' + position + '"]');
				const targetPos = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 16;
				if (target.closest(".program__tab") !== null) {
					//遷移したい箇所がタブだった場合の処理
					target.previousElementSibling.checked = true;
					const index = Array.from(target.closest(".program__tab").querySelectorAll(".js-tabs > input")).findIndex((tab) => tab.checked);
					toggleTab(index);
				}
				window.scrollTo({
					top: targetPos,
					behavior: "smooth",
				});
			});
		});
	}

	//  *------------------------------------------*
	//  プログラム詳細 タブ
	//  *------------------------------------------*
	const tabs = document.querySelectorAll('.js-tabs input[name="programTab"]');
	const tabContents = document.querySelectorAll(".js-tabs-item");
	const checkedIndex = Array.from(tabs).findIndex((tab) => tab.checked);
	function showTab() {
		if (checkedIndex >= 0 && checkedIndex < tabContents.length) {
			tabContents.forEach(function (content, index) {
				content.style.display = index === checkedIndex ? "block" : "none";
			});
			tabs.forEach(function (tab, index) {
				tab.addEventListener("change", function () {
					toggleTab(index);
				});
			});
		}
	}
	function toggleTab(index) {
		if (index >= 0 && index < tabContents.length) {
			tabContents.forEach(function (e) {
				e.style.display = "none";
			});
			tabContents[index].style.display = "block";
		}
	}
	showTab();

	//  *------------------------------------------*
	//  シェア
	//  *------------------------------------------*
	const share = document.querySelector(".js-share");
	if (share !== null) {
		const url = share.querySelector(".url input");
		url.value = location.href;
		const copy = share.querySelector(".copy a");
		copy.addEventListener("click", function () {
			navigator.clipboard.writeText(url.value);
			new Promise((resolve) => {
				copy.nextElementSibling.classList.add("is-active");
				resolve();
			}).then(() => {
				setTimeout(() => {
					copy.nextElementSibling.classList.remove("is-active");
				}, 2000);
			});
		});
	}

	//  *------------------------------------------*
	//  アコーディオン
	//  *------------------------------------------*
	const accordion = document.querySelectorAll(".js-accordion");
	if (accordion.length) {
		accordion.forEach(function (e, index) {
			const eBtn = e.querySelector(".js-accordion-btn");
			let eCont = e.querySelector(".js-accordion-cont");
			let eMoreBtn = e.querySelector(".js-more-btn") !== null ? e.querySelector(".js-more-btn") : null;
			const eContStyle = window.getComputedStyle(eCont, null);
			const eContLineHeight = Math.ceil(eContStyle.getPropertyValue("line-height").replace(/[^0-9.]/g, ""));
			const row = 7;
			const hLimit = eContLineHeight * row + 1;
			const eBtnH = eBtn.offsetHeight;
			let eOpenH = eBtnH + eCont.offsetHeight + 1;
			window.addEventListener("resize", function () {
				eOpenH = eBtnH + eCont.offsetHeight + 1;
				toggleAccordion(e);
			});
			if (hLimit > eCont.offsetHeight) {
				e.classList.remove("desc");
				e.classList.add("is-active");
				if (eMoreBtn !== null) {
					eMoreBtn.remove();
					eMoreBtn = null;
				}
			}
			function toggleAccordion(target) {
				if (target.classList.contains("is-active")) {
					target.style.height = eOpenH + "px";
					if (eMoreBtn !== null) {
						new Promise((resolve) => {
							eMoreBtn.classList.add("is-hidden");
							resolve();
						}).then(() => {
							setTimeout(() => {
								eMoreBtn.style.display = "none";
							}, 160);
						});
					}
				} else {
					if (eMoreBtn !== null) {
						target.style.height = eBtnH + hLimit + "px";
						if (eMoreBtn) {
							new Promise((resolve) => {
								eMoreBtn.style.display = "block";
								resolve();
							}).then(() => {
								eMoreBtn.classList.remove("is-hidden");
							});
						}
					} else {
						target.style.height = eBtnH + "px";
					}
				}
			}
			toggleAccordion(e);
			eBtn.addEventListener("click", function () {
				e.classList.toggle("is-active");
				toggleAccordion(e);
			});
			if (eMoreBtn) {
				eMoreBtn.addEventListener("click", function () {
					e.classList.toggle("is-active");
					toggleAccordion(e);
				});
			}
		});
	}
});

//  *------------------------------------------*
//  スムーススクロール
//  *------------------------------------------*
document.addEventListener("DOMContentLoaded", function () {
	// aタグのクリックイベントに対してスムーススクロールを実行
	document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
		anchor.addEventListener("click", function (event) {
			event.preventDefault(); // デフォルトのアンカーリンク動作を無効化

			const targetId = this.getAttribute("href");
			const targetElement = document.querySelector(targetId);

			if (targetElement) {
				// スクロール位置を計算（16px上に調整）
				const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - 16;

				window.scrollTo({
					top: targetPosition,
					behavior: "smooth", // スムーススクロールを有効化
				});
			}
		});
	});
});
