<template>
    <div class="breadcrumb">
        <router-link to="/">{{ $t('projects.index') }}</router-link>
        <i class="icon-arrow-right title-bar-arrow"></i>
        {{ project.title }}
    </div>
</template>

<script>
    export default {

    }
</script>