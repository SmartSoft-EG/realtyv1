<route lang="yaml">
meta:
 auth: true
</route>
<template>

<d-page>
    <vr-table :headers="['name','telephone','email','job']" :data="$auth.user()">  </vr-table>
</d-page>

</template>

