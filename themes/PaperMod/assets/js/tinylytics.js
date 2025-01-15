let path = window.location.pathname
let should_collect = localStorage.getItem("tinylytics_ignore") == null;
let current_url = encodeURIComponent(window.location.href)
let collect_url = "https://tinylytics.app/collector/4DLmhCMxs6TsUw5kZiy5"
let set_ignore_param = new URLSearchParams(document.location.search)?.get("tiny_ignore") || new URLSearchParams(document.location.search)?.get("ti")
let referrer = document.referrer.indexOf(window.location.href) < 0 ? document.referrer : ""
if(set_ignore_param){
  if(set_ignore_param === "true" && localStorage.getItem("tinylytics_ignore") == null){
    localStorage.setItem("tinylytics_ignore", true)
    should_collect = false
    alert("tinylytics will no longer track your own hits in this browser.")
  }
  else if (set_ignore_param === "false" && localStorage.getItem("tinylytics_ignore") != null){
    localStorage.removeItem("tinylytics_ignore")
    should_collect = true
    alert("tinylytics has been enabled for this website, for this browser.")
  }
  if(set_ignore_param === "true"){
    localStorage.setItem("tinylytics_ignore", true)
  }
}
if(should_collect){
  fetch(`${collect_url}?url=${current_url}&path=${path}&referrer=${referrer}`, {method: "post"})
}
