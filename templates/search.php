<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search ?></span>»</h2>
        <?php
        if ($ads): ?>
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
                            <span class="lot__category"><?= htmlspecialchars($ad['category_id']); ?></span>
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
        <?php
        else: ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php
        endif; ?>

    </section>
    <?php
    if ($pages_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <?php
                if ($cur_page > 1): ?>
                    <a href="/search.php?search=<?= $search; ?>&page=<?= $cur_page - 1; ?>">Назад</a>
                <?php
                else: ?>
                    <a>Назад</a>
                <?php
                endif; ?>
            </li>
            <?php
            foreach ($pages as $page): ?>
                <li class="pagination-item <?= ($page == $cur_page) ? 'pagination-item-active' : '' ?>">
                    <a href="/search.php?search=<?= $search; ?>&page=<?= $page; ?>"><?= $page; ?></a>
                </li>
            <?php
            endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <?php
                if ($cur_page < count($pages)) : ?>
                    <a href="/search.php?search=<?= $search; ?>&page=<?= $cur_page + 1; ?>">Вперед</a>
                <?php
                else: ?>
                    <a>Вперед</a>
                <?php
                endif; ?>
            </li>
        </ul>
    <?php
    endif; ?>
</div>
