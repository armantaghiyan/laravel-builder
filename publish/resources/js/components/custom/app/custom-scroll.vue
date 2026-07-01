<template>
    <div class="custom-scroll">
        <slot/>
    </div>
</template>

<style>
.custom-scroll {
    overflow-y: auto;
    height: 100%;
}

.custom-scroll::-webkit-scrollbar {
    width: 6px;
}

.custom-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scroll::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    transition: background-color 200ms ease;
}

.custom-scroll::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.35);
}

.custom-scroll {
    scrollbar-width: thin;
    scrollbar-color: #a3a9c2 transparent;
}
</style>
