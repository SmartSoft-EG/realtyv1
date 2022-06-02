<route lang="yaml">
name: control_panel
meta:
 auth: true
 hide: true
</route>
<template>

    <d-page>
        <vr-table :headers="['name', 'telephone', 'email', 'job']" :data="$auth.user()"> </vr-table>
    </d-page>

</template>

