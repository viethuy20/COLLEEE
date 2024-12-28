

document.addEventListener(
	"DOMContentLoaded",
	function () {
		if (document.getElementsByClassName("modal").length) {
			const modal = Array.from(document.getElementsByClassName("modal"));
			const modalOpen = Array.from(document.getElementsByClassName("js-modal-open"));
			const modalClose = Array.from(document.getElementsByClassName("js-modal-close"));
			const modalOverlay = Array.from(document.getElementsByClassName("js-modal-overlay"));
			const backfaceFixed = (fixed) => {
				/**
				 * 表示されているスクロールバーとの差分を計測し、背面固定時はその差分body要素に余白を生成する
				 */
				const scrollbarWidth = window.innerWidth - document.body.clientWidth;
				document.body.style.paddingRight = fixed ? `${scrollbarWidth}px` : "";
				document.querySelector("header").style.paddingRight = fixed ? `${scrollbarWidth}px` : "";
				/**
				 * スクロール位置を取得する要素を出力する(`html`or`body`)
				 */
				const scrollingElement = () => {
					const browser = window.navigator.userAgent.toLowerCase();
					if ("scrollingElement" in document) return document.scrollingElement;
					if (browser.indexOf("webkit") > 0) return document.body;
					return document.documentElement;
				};
				/**
				 * 変数にスクロール量を格納
				 */
				let scrollY = fixed ? scrollingElement().scrollTop : parseInt(document.body.style.top || "0");
				/**
				 * CSSで背面を固定
				 */
				const styles = {
					height: "100vh",
					left: "0",
					verflow: "hidden",
					position: "fixed",
					top: `${scrollY * -1}px`,
					width: "100vw",
				};
				Object.keys(styles).forEach((key) => {
					document.body.style[key] = fixed ? styles[key] : "";
				});
				/**
				 * 背面固定解除時に元の位置にスクロールする
				 */
				if (document.getElementsByClassName("modal scroll").length) {
					window.scrollTo(0, scrollingElement().scrollTop);
				} else if (!fixed) {
					window.scrollTo(0, scrollY * -1);
				}
			};
			function closeModal() {
				function closeModalInner(target) {
					target.addEventListener("click", function () {
						if (target.closest(".alert-modal") === null) {
							if (target.classList.contains("js-modal-close") || target.classList.contains("js-modal-overlay")) {
								target.closest(".modal").classList.add("is-close");
								target.closest(".modal").classList.remove("is-open");
								setTimeout(function () {
									target.closest(".modal").style.display = "none";
									backfaceFixed(false);
								}, 301);
								return;
							}
						}
					});
				}
				modalClose.forEach(function (target) {
					closeModalInner(target);
				});
				modalOverlay.forEach(function (target) {
					closeModalInner(target);
				});
			}
			function openModal() {
				const showModal = (modalSelector) => {
					modal.forEach((e) => {
						if (e.dataset.modal === modalSelector) {
							e.classList.add("is-open");
							e.classList.remove("is-close");
							e.style.display = "block";
							backfaceFixed(true);
						}
					});
                };

				modalOpen.forEach((target) => {
					const dataModalOpen = target.dataset.modalOpen;
					const isAutoOpen = target.dataset.autoOpen === "true";
					if (isAutoOpen) {
						showModal(dataModalOpen);
					}
					target.addEventListener("click", () => showModal(dataModalOpen));
				});
			}
		}
		function alertModal() {
			if (document.getElementsByClassName("alert-modal").length) {
				const alertModals = Array.from(document.getElementsByClassName("alert-modal"));
				alertModals.forEach(function (target) {
					target.classList.add("fadeIn");
					if (target.querySelector(".js-modal-close") != null) {
						target.querySelector(".js-modal-close").addEventListener("click", function () {
							target.classList.remove("fadeIn");
							target.classList.add("is-close");
							setTimeout(function () {
								target.style.display = "none";
							}, 301);
						});
					}
					setTimeout(function () {
						target.classList.remove("fadeIn");
						target.classList.add("is-close");
						setTimeout(function () {
							target.style.display = "none";
						}, 301);
					}, 5000);
				});
			}
		}

		window.addEventListener("load", function () {
			closeModal();
			openModal();
			alertModal();
		});
	},
	false
);

document.addEventListener("DOMContentLoaded", function() {
    // セール期間中かどうかの判定
	var modalOpenElement = document.querySelector('.js-modal-open');
	var isSale = false;
	if (modalOpenElement) {
		var saleTime = modalOpenElement.getAttribute('data-is-time-sale');
		isSale  = saleTime > 0 ;
	}
	// セール期間中の場合はtrueに設定
    if (isSale) {
        document.querySelectorAll('.js-sale-ad-modal').forEach(function(element) {
            element.style.display = 'block';
        });
    } else {
        document.querySelectorAll('.js-sale-ad-modal').forEach(function(element) {
            element.style.display = 'none';
        });
    }
});

