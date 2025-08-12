<div class="mypageDiv">
	<? if(isset($data) && count($data) > 0) { ?>
		<? foreach($data as $index => $row) { ?>
			<div class="mypageProDiv" >
				<div class="mypageProTitle"><?= $row['cpc_name'] ?></div>
				<div class="mypageLine"></div>
				<div class="mypageProText"><?= $row['cpc_dis_shop_name'] ?></div>
				<div class="mypageProText2">발급 <?= date('y.n.d', strtotime($row['cpc_start_date'])) . ' - ' . date('y.n.d', strtotime($row['cpc_end_date'])) ?></div>
				<div class="mypageProText2"><?= $row['cpc_dis_memo'] ?></div>
			</div>
		<? } ?>
	<? } else { ?>
		<div style="height: 56px"></div>
		<div class="Cart_cart-empty__7e2tB">
			<div class="MyNone_my-none__9dt6+">
				<img src="/common/homepage/img/basket/ico_none.svg" alt="">
				<p class="MyNone_my-none__text__24uCV">발급 받은 혜택이 없어요</p>
			</div>
		</div>
	<? } ?>
</div>

<div class="mypageCouponDiv">
	<div class="mypageCouponTitle">
		혜택 사용 안내
	</div>
	<div>
		<ul style="">
			<li style=""><p>사용기간이 지난 혜택은 자동으로 소멸되며, 재발행 되지 않습니다.</p></li>
			<li style=""><p>할인 혜택별 적용 대상 상품이 다를 수 있으며, 적용 대상 상품의 범위는 당사 사정에 따라 사전고지 없이 변동될 수 있습니다.</p></li>
			<li style=""><p>할인 혜택의 할인금액이 상품의 판매가 또는 총 주문 금액을 초과할 경우 사용 불가능합니다.</p></li>
		</ul>
	</div>
</div>
