<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
        снаряжение.</p>
    <ul class="promo__list">
        <?php
        foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['code']; ?>">
                <a class="promo__link" href="/all_lots.php?category_name=<?=  htmlspecialchars($category['code']); ?>""><?= $category['name']; ?></a>
            </li>
        <?php
        endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php
        foreach ($ads as $ad): ?>
            <?php
            $res = get_data_range($ad['date_end']); ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $ad['img']; ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= get_name_from_id($categories, $ad['category_id']); ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?id=<?= $ad['id']; ?>"><?= htmlspecialchars(
                                $ad['name']
                            ); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= price_format($ad['start_price']); ?></span>
                        </div>
                        <div class="lot__timer timer<?= ($res[0] < 1) ? 'timer--finishing' : '' ?> ">
                            <?= implode(' : ', $res); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php
        endforeach; ?>
    </ul>
</section>
