
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets  as $bet): ?>
            <?php $res = get_data_range($bet['date_end']); ?>
            <tr class="rates__item  <?= ($bet['winner_id'] == $user_id) ? 'rates__item--win' : ''; ?><?= time() > strtotime($bet['date_end']) && $bet['winner_id'] !== $user_id ? 'rates__item--end' : '' ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $bet['img'] ?>" width="54" height="40" alt="<?= $bet['name'] ?>">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= $bet['id']; ?>"><?= $bet['name'] ?></a></h3>
                    <?php if ($bet['winner_id'] == $user_id): ?>
                    <p><?= $bet['contacts'] ?></p>
                    <?php endif; ?>
                </td>
                <td class="rates__category">
                    <?= get_name_from_id($categories, $bet['category_id']); ?>
                </td>

                <td class="rates__timer">
                    <?php if ($bet['winner_id'] == $user_id): ?>
                        <div class="timer timer--win">
                            Ставка выиграла
                        </div>
                    <?php elseif (time() > strtotime($bet['date_end']) && $bet['winner_id'] !== $user_id): ?>
                        <div class="timer timer--end">Торги окончены</div>
                    <?php else: ?>
                        <div class="timer<?= ($res[0] < 1) ? 'timer--finishing': '' ?> "><?= implode(' : ', $res);?></div>
                    <?php endif; ?>
                </td>
                <td class="rates__price">
                    <?= price_format($bet['max_price']); ?>
                </td>
                <td class="rates__time">
                    <?= $bet['latest_date'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
