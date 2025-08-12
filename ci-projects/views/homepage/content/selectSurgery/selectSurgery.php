<div class="SelectSurgery_select-surgery__huyL8">
	<?/*
	<div class="SelectSurgery_benefit-area__d6gSo">
		사용 가능 혜택(3)<button>보기</button>
	</div>
	*/?>
	<h3>
		<p style="font-size: 14px; font-weight: 500;">
			<?if ( !$this->session->userdata('M_PUBLIC_CI') ) {?>
				<span>
					지금 로그인하고<br>다양한 <b>이벤트 혜택</b>을 확인하세요
				</span>
				<a href="<?=getenv('LOGIN_URL')?>" >
					<button class="SelectSurgery_log-btn__J1GuY">회원가입/로그인</button>
				</a>
			<?} else {?>
				<span style="font-weight: bold; font-size: 18px;">
					<?=$this->session->userdata('M_NAME')?>님 반갑습니다.
				</span>
				<span>
					<br>원하시는 상품을 선택해 주세요
				</span>
				<a href="#?>" >
					<button class="SelectSurgery_log-btn__J1GuY">바로예약</button>
				</a>
			<?} ?>
		</p>
	</h3>
	<div class="SelectSurgery_category-wrap__+GKZq">
		<div id="swipercate1">
			<div class="swiper swiper-initialized swiper-horizontal swiper-ios SelectSurgery_depth1__olYz9">
				<div class="swiper-wrapper">
					<?if ( isset( $mainCategory ) ) {?>
						<?foreach ( $mainCategory as $index => $row ) {?>
							<div
								class="m_i m_i_<?=$row['uid']?> swiper-slide SelectSurgery_item__hyhX2 <?=$index === 0 ? 'SelectSurgery_active__Yw0Oo' : ''?>"
								onclick="mainCategoryFun('<?=$row['uid']?>', 'productList', '<?=$index?>')"
							>
								<?=$row['name']?>
							</div>
						<?}?>
					<?}?>
<!--					<div class="swiper-slide SelectSurgery_item__hyhX2">이벤트2</div>-->
				</div>
			</div>
			<button onclick="totalCategoryViewShowFun()">전체보기</button>
		</div>
	</div>

	<?/*
	<div class="SelectSurgery_depth2-wrap__ItSPM">
		<div id="swipercate2">
			<div class="swiper swiper-initialized swiper-horizontal swiper-ios SelectSurgery_depth2__knR6f">
				<div class="swiper-wrapper">
					<?if ( isset($subCategory) ) {
						foreach ( $subCategory as $index => $row ) {
//								echo $index."&nbsp;&nbsp;";
							if ( $index === 'event' ) {
								foreach ( $row as $index2 => $row2 ) {?>
									<div
										class="v_i <?='v_'.$index?> swiper-slide SelectSurgery_item__hyhX2 <?=$index2 === 0 ? 'SelectSurgery_active__Yw0Oo' : ''?>"
										onclick="subCategoryFun('<?=$row2['uid']?>', 'productList')"
										data-uid="<?=$row2['uid']?>"
									>
										<?=$row2['name']?>
									</div>
								<?}
							}
						}
					}?>
				</div>
			</div>
		</div>
	</div>
	*/?>

	<div id="productList">

	</div>

	<div id="setCouponData"></div>

</div>


<?if ( !$this->session->userdata('M_PUBLIC_CI') ) {?>
<div id="surgeryBenefitView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: block;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="surgeryBenefitViewHideFun()"></span>
			</div>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 20px;">
				<?//echo "<pre>"; print_r($branchInfo); echo "<pre>";?>
				<div class="Categorys_categorys__5pPjP">
					<ul>

						<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: center; color: #111;">
							<h4 style="font-size: 18px; line-height: 30px;">
								지금 로그인하지 않으면 손해!
								<br />
								쁨클리닉 회원에게 드리는 이 달의 스페셜 혜택
							</h4>
						</li>

						<li>
							<a href="">
								<button style="width: 100%; height: 52px; margin-top: 24px; text-align: center; border-radius: 8px; background-color: #cf2f75;
									font-size: 16px; font-weight: 600; color: #fff;">
									<table style="margin: auto;">
										<tr>
											<td style="vertical-align: middle; padding-top: 2px; padding-left: 5px;">로그인하고 모든 혜택 받기</td>
										</tr>
									</table>
								</button>
							</a>
						</li>

					</ul>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="surgeryBenefitViewHideFun()"></button>
</div>
<?}?>

<script>

	$(document).ready(function() {
		// 화면 진입시 최초 실행
		mainCategoryFun('<?=$mainCategory[0]['uid']?>', 'productList', '0');
	})
</script>



