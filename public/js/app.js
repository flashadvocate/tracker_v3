var Tracker=Tracker||{};!function(e){Tracker={Setup:function(){Tracker.SearchMembers(),Tracker.AnimateCounter(),Tracker.SearchCollection()},SearchMembers:function(){this.TriggerFilter(document.getElementById("member-search"),this.DoMemberSearch,1e3)},TriggerFilter:function(e,t,r){var n=null;e.onkeypress=function(){n&&window.clearTimeout(n),n=window.setTimeout(function(){n=null,t()},r)},e=null},DoMemberSearch:function(){if(e("#member-search").val()){var t=e("input#member-search").val();e.ajax({url:"/members/search/"+t,type:"GET",success:function(t){e("#member-search-results").html(t)}})}},AnimateCounter:function(){e(".count-animated").each(function(){var t=e(this);e({Counter:0}).animate({Counter:t.text()},{duration:3e3,easing:"easeOutQuart",step:function(){t.hasClass("percentage")?t.text(Tracker.FormatNumber(Math.ceil(this.Counter)+"%")):t.text(Tracker.FormatNumber(Math.ceil(this.Counter)))}})})},FormatNumber:function(e){return e.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,"$1,")},SearchCollection:function(){e("#search-collection").keyup(function(){var t=e(this).val(),r=new RegExp("^"+t,"i"),n=".collection .collection-item";e(n).each(function(){var t=r.test(e(this).text());e(this).toggle(t)})})}}}(jQuery),Tracker.Setup();