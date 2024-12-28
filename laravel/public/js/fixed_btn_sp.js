window.addEventListener("load", function () {
	if (document.getElementsByClassName("js-fixed-btn").length) {
		const fixedBtn = Array.from(document.getElementsByClassName("js-fixed-btn"));

		window.addEventListener("scroll", function () {
			let scrollY = window.scrollY;
			let wH = window.innerHeight;
			const footer = document.querySelector("footer");
			const tabBerH = document.querySelector(".tabbar").offsetHeight;
			let rectFooter = footer.getBoundingClientRect();
			let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			let footerPos = rectFooter.top + scrollTop;
			fixedBtn.forEach(function (target) {
				target.style.marginBottom = tabBerH + "px";
			});
			if (document.getElementById("js-fixed-point") !== null) {
				let fixedPoint = document.getElementById("js-fixed-point");
				let rect = fixedPoint.getBoundingClientRect();
				let pointPos = rect.top + scrollTop;
				if (scrollY + wH < pointPos) {
					fixedBtn.forEach(function (target) {
						target.classList.remove("is-active");
						setTimeout(function () {
							switchDisplay(target);
						}, 301);
					});
				} else if (scrollY + wH >= footerPos) {
					fixedBtn.forEach(function (target) {
						target.classList.remove("is-active");
						setTimeout(function () {
							switchDisplay(target);
						}, 301);
					});
				} else if (scrollY + wH >= pointPos) {
					fixedBtn.forEach(function (target) {
						switchDisplay(target);
						target.classList.add("is-active");
					});
				}
			} else if (document.getElementById("js-fixed-dup") !== null) {
				let fixedDup = document.getElementById("js-fixed-dup");
				let rect = fixedDup.getBoundingClientRect();
				let dupPos = rect.bottom + scrollTop;
				if (scrollY < dupPos) {
					fixedBtn.forEach(function (target) {
						target.classList.remove("is-active");
						setTimeout(function () {
							switchDisplay(target);
						}, 301);
					});
				} else if (scrollY + wH >= footerPos) {
					fixedBtn.forEach(function (target) {
						target.classList.remove("is-active");
						setTimeout(function () {
							switchDisplay(target);
						}, 301);
					});
				} else if (scrollY >= dupPos) {
					fixedBtn.forEach(function (target) {
						switchDisplay(target);
						target.classList.add("is-active");
					});
				}
			} else {
				if (scrollY + wH >= footerPos) {
					fixedBtn.forEach(function (target) {
						target.classList.remove("is-active");
						setTimeout(function () {
							switchDisplay(target);
						}, 301);
					});
				} else {
					fixedBtn.forEach(function (target) {
						switchDisplay(target);
						target.classList.add("is-active");
					});
				}
			}
			if (scrollY == 0) {
				fixedBtn.forEach(function (target) {
					target.classList.remove("is-active");
					setTimeout(function () {
						switchDisplay(target);
					}, 301);
				});
			}
		});
		function switchDisplay(e) {
			if (e.classList.contains("is-active") == true) {
				e.style.display = "block";
				return false;
			}
			if (e.classList.contains("is-active") !== true) {
				e.style.display = "none";
				return false;
			}
		}
	}
});
