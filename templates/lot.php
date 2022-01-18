<section class="lot-item container">
    <?php
    $res = get_data_range($lot['date_end']); ?>
    <h2><?= htmlspecialchars($lot['name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['img']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['categories']); ?></span></p>
            <p class="lot-item__description"><?= $lot['description']; ?></p>
        </div>
        <div class="lot-item__right">
            <?php
            if (isset($_SESSION['user'])): ?>
                <div class="lot-item__state">
                    <div class="lot__timer timer<?= ($res[0] < 1) ? 'timer--finishing' : '' ?> ">
                        <?= implode(' : ', $res); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format($now_price); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format($min_price); ?></span>
                        </div>
                    </div>
                    <?php if($show_bet_form): ?>
                    <form class="lot-item__form" action="lot.php?id=<?= $lot['id'] ?>" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?= price_format($min_price); ?>">
                            <?php
                            if (!empty($errors['cost'])): ?>
                                <span class="form__error"><?= $errors['cost']; ?></span>
                            <?php
                            endif; ?>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                    <?php endif; ?>
                </div>
            <?php
            endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= count($bets); ?></span>)</h3>
                <table class="history__list">
                    <?php
                    foreach ($bets as $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= $bet['user']; ?></td>
                            <td class="history__price"><?= price_format($bet['price']); ?></td>
                            <td class="history__time"><?= $bet['date']; ?></td>
                        </tr>
                    <?php
                    endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
