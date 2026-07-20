<template>
    <h3>Избранное</h3>

    <div class="acc-selected">
        <div class="acc-selected__box">
            <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M36 4.5C18.6047 4.5 4.5 18.6047 4.5 36C4.5 53.3953 18.6047 67.5 36 67.5C53.3953 67.5 67.5 53.3953 67.5 36C67.5 18.6047 53.3953 4.5 36 4.5ZM49.5 37.6875C49.5 37.9969 49.2469 38.25 48.9375 38.25H23.0625C22.7531 38.25 22.5 37.9969 22.5 37.6875V34.3125C22.5 34.0031 22.7531 33.75 23.0625 33.75H48.9375C49.2469 33.75 49.5 34.0031 49.5 34.3125V37.6875Z" fill="black" fill-opacity="0.45"/>
            </svg>
            <div>
                У вас еще нет избранных товаров

                <span>Вы можете добавить в избранное любой товар</span>
            </div>
            <a href="/shop" class="btn">В каталог</a>
        </div>
    </div>
</template>

<script>
    export default{
        name: "AccountSelectedProducts",
    }
</script>

<style>
    .acc-selected{
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .acc-selected__box{
        max-width: 330px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 24px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        line-height: 32px;

        span{
            display: block;
            font-size: 14px;
            font-weight: 400;
            color: rgba(0, 0, 0, 0.45);
        }

        .btn{
            padding: 5px 15px;
            font-size: 14px;
            height: 32px;
            font-weight: 400;
        }
    }
</style>
