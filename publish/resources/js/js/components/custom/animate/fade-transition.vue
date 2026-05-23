<template>
    <Transition>
        <slot/>
    </Transition>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 300ms ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
