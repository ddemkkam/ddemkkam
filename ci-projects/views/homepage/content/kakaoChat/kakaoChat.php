<?/*
<!-- AceCounter Log Gathering Script V.8.0.AMZ2019080601 -->
<script language='javascript'>
	var _AceGID=(function(){var Inf=['gtp1.acecounter.com','8080','AH1A42876674360','AW','0','NaPm,Ncisy','ALL','0']; var _CI=(!_AceGID)?[]:_AceGID.val;var _N=0;var _T=new Image(0,0);if(_CI.join('.').indexOf(Inf[3])<0){ _T.src ="https://"+ Inf[0] +'/?cookie'; _CI.push(Inf);  _N=_CI.length; } return {o: _N,val:_CI}; })();
	var _AceCounter=(function(){var G=_AceGID;var _sc=document.createElement('script');var _sm=document.getElementsByTagName('script')[0];if(G.o!=0){var _A=G.val[G.o-1];var _G=(_A[0]).substr(0,_A[0].indexOf('.'));var _C=(_A[7]!='0')?(_A[2]):_A[3];var _U=(_A[5]).replace(/\,/g,'_');_sc.src='https:'+'//cr.acecounter.com/Web/AceCounter_'+_C+'.js?gc='+_A[2]+'&py='+_A[4]+'&gd='+_G+'&gp='+_A[1]+'&up='+_U+'&rd='+(new Date().getTime());_sm.parentNode.insertBefore(_sc,_sm);return _sc.src;}})();
</script>
<!-- AceCounter Log Gathering Script End -->
*/?>
<!-- 전환페이지 설정 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
<script type="text/javascript">
	var _nasa={};
	_nasa["cnv"] = wcs.cnv("5","10"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script>

<!-- 공통 적용 스크립트 , 모든 페이지에 노출되도록 설치. 단 전환페이지 설정값보다 항상 하단에 위치해야함 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"> </script>
<script type="text/javascript">
	if (!wcs_add) var wcs_add={};
	wcs_add["wa"] = "<?=isset($branchInfo['H_NAVER_SCRIPT']) ? $branchInfo['H_NAVER_SCRIPT'] : ''?>";
	if (!_nasa) var _nasa={};
	wcs.inflow();
	wcs_do(_nasa);
</script>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="0;url=<?=isset($branchInfo['H_KAKAO_CHAT']) ? $branchInfo['H_KAKAO_CHAT'] : ''?>">
