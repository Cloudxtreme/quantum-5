window.onload = function () 
{
	/**
	 * Cookie setter.
	 * 
	 * @param string cookieKey   Key.
	 * @param string value       Value.
	 * @param int    expire      Expire time.
	 * 
	 * @returns void
	 */
	function setCookie(cookieKey, value, expire)
	{
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + expire);
		var cookieValue = escape(value) + ((expire == null) ? "" : "; expires=" + exdate.toUTCString());
		document.cookie = cookieKey + "=" + cookieValue;
	}

	/**
	 * Cookie getter.
	 * 
	 * @param string cookieKey   Key.
	 * 
	 * @returns string  Cookie value.
	 */
	function getCookie(cookieKey)
	{
		var cookieValue = document.cookie,
			cookieStart = cookieValue.indexOf(" " + cookieKey + "=");

		if (cookieStart == -1) {
			cookieStart = cookieValue.indexOf(cookieKey + "=");
		}

		if (cookieStart == -1) {
			cookieValue = null;
		}
		else {
			cookieStart   = cookieValue.indexOf("=", cookieStart) + 1;
			var cookieEnd = cookieValue.indexOf(";", cookieStart);

			if (cookieEnd == -1) {
				cookieEnd = cookieValue.length;
			}
			cookieValue = unescape(cookieValue.substring(cookieStart, cookieEnd));
		}

		return cookieValue;
	}
}