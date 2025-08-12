<div style="max-width: 570px; margin: auto;">
	<div class="rules_rules__-8x-K">
		<table class="serviceTable">
			<tr>
				<td class="serviceText" style="vertical-align: top; padding-top: 5px;">
					전체 메뉴
				</td>
				<td class="serviceText" style="">
					<div class="iconClose" onclick="all_menu_fun_hide()"></div>
				</td>
			</tr>
		</table>

		<div style="width: 100%; height: 82px; padding: 16px 0px 16px 0px; border-radius: 8px; background-color: #cf2f75;">
			<table style="width: 100%; height: 50px; margin: auto;">
				<tr>
					<?
					$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
					if( preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']) ) {	?>
					<td class="all_menu_top" >
						<a href="tel:<?=isset($branchInfo['H_CONTACT1_2']) ? $branchInfo['H_CONTACT1_2'] : ''?>">
							<img src="/common/homepage/img/common/all_menu_call.svg">
							<br />
							전화 상담
						</a>
					</td>
					<?}?>
					<? if ($branchInfo['BRANCH'] != 'ppeum27') { ?>
						<td class="all_menu_top" >
							<a href="/kakaoChat" target="_blank">
								<img src="/common/homepage/img/common/all_menu_kakao.svg">
								<br />
								카톡 상담
							</a>
						</td>
					<? } ?>
					<td class="all_menu_top" style="<? if ($branchInfo['BRANCH'] != 'ppeum27') { ?>border-right: 0px;<? } ?>cursor: pointer;" onclick="reservation()">
						<img src="/common/homepage/img/common/all_menu_res.svg">
						<br />
						바로 예약
					</td>
					<? if ($branchInfo['BRANCH'] == 'ppeum27') { ?>
						<td class="all_menu_top" style="border-right: 0px;cursor: pointer;" onclick="location.href='/mypage'">
							<img src="/common/homepage/img/footer/ico_my_on.svg" style="filter: brightness(0) invert(1);">
							<br />
							마이페이지
						</td>
					<? } ?>
				</tr>
			</table>
		</div>

	</div>

	<div class="rules_rules__-8x-K">
		<table class="serviceTable">
			<tr>
				<td class="serviceText" style="height: 35px;">
					<? if (isset($branchInfo['H_PVL_KAKAO_CHAT']) && !empty($branchInfo['H_PVL_KAKAO_CHAT'])) { ?>
						일반 예약/PVL 예약
					<? } else { ?>
						예약
					<? } ?>
				</td>
				<td class="serviceText" style="">

				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/mypage?type=2'" style="cursor: pointer;">
					예약 확인 / 변경/ 취소
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/mypage?type=2">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/mypage?type=3'" style="cursor: pointer;">
					잔여 시술 조회 / 예약
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/mypage?type=3">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<? if (isset($branchInfo['H_PVL_KAKAO_CHAT']) && !empty($branchInfo['H_PVL_KAKAO_CHAT'])) { ?>
				<tr>
					<td class="allMenuText" onclick="javascript:pvlCounselViewShowFun()" style="cursor: pointer;">
						PVL 예약하기
					</td>
					<td class="serviceText" style="text-align: right; vertical-align: middle;">
						<a href="javascript:pvlCounselViewShowFun()">
							<img src="/common/homepage/img/common/ico_more.svg">
						</a>
					</td>
				</tr>
			<? } ?>
		</table>
	</div>

	<div style="height: 1px; border-bottom: 1px solid #e7e8eb;"></div>

	<div class="rules_rules__-8x-K">
		<table class="serviceTable">
			<tr>
				<td class="serviceText" style="height: 35px;">
					이벤트 · 시술예약
				</td>
				<td class="serviceText" style="">

				</td>
			</tr>
			<tr>
				<td class="allMenuText"  onclick="location.href='/event'" style="cursor: pointer;">
					진행 중 이벤트
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/event">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/selectSurgery'" style="cursor: pointer;">
					시술예약/가격
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/selectSurgery">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
		</table>
	</div>

	<div style="height: 1px; border-bottom: 1px solid #e7e8eb;"></div>

	<div class="rules_rules__-8x-K">
		<table class="serviceTable">
			<tr>
				<td class="serviceText" style="height: 35px;">
					소개
				</td>
				<td class="serviceText" style="">

				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/about?type=3'" style="cursor: pointer;">
					오시는 길 / 진료 시간
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/about?type=3">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/about?type=1'" style="cursor: pointer;">
					둘러보기
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/about?type=1">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/about?type=2'" style="cursor: pointer;">
					의료진 소개
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/about?type=2">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<tr>
				<td class="allMenuText" onclick="location.href='/notice'" style="cursor: pointer;">
					공지사항
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="/notice">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
			<tr style="display: none;">
				<td class="allMenuText">
					인재채용
				</td>
				<td class="serviceText" style="text-align: right; vertical-align: middle;">
					<a href="">
						<img src="/common/homepage/img/common/ico_more.svg">
					</a>
				</td>
			</tr>
		</table>
	</div>
</div>
