

<template>
  <div>
    <van-button type="primary" @click="showconfirm">show confirm</van-button>

    <van-form @submit="onSubmit">
      <van-cell-group inset>
        <van-field v-model="d.username" name="Username" label="Username" placeholder="Username"
          :rules="[{ required: true, message: 'Username is required' }]" />
        <van-field v-model="d.password" type="password" name="Password" label="Password" placeholder="Password"
          :rules="[{ required: true, message: 'Password is required' }]" />
        <van-field name="group" label="CheckboxGroup">
          <template #input>
            <van-checkbox-group v-model="d.groupChecked" direction="horizontal">
              <van-checkbox class="m-2" name="ee" shape="square">Checkbox 1</van-checkbox>
              <van-checkbox class="m-2" name="rr">Checkbox 2</van-checkbox>
              <van-checkbox class="m-2" name="uu">Checkbox 2</van-checkbox>

              <van-checkbox class="m-2" name="tt">Checkbox 2</van-checkbox>

              <van-checkbox name="ww">Checkbox 2</van-checkbox>

              <van-checkbox name="qq">Checkbox 2</van-checkbox>

            </van-checkbox-group>
          </template>
        </van-field>

        <van-field name="uploader" label="Uploader">
          <template #input>
            <van-uploader v-model="d.file" />
          </template>
        </van-field>

        <van-field v-model="d.result" is-link readonly name="picker" label="Picker" placeholder="Select city"
          @click="showPicker = true" />

        <van-popup v-model:show="showPicker" overlay closeOnPopstate closeable>
          <van-picker :columns="columns" @confirm="onConfirm" @cancel="showPicker = false" />
        </van-popup>

      </van-cell-group>
      <div style="margin: 16px;">
        <van-button round block type="primary" native-type="submit">
          Submit
        </van-button>
      </div>
    </van-form>



    {{ selection }}

    <v-btn color="success" @click="test([7])">select 1</v-btn>
    <v-btn color="success" @click="test([8])">select 2</v-btn>
    <v-btn color="success" @click="test([])">reset</v-btn>
    <v-btn color="success" @click="setActive(1)">active 1</v-btn>
    <v-btn color="success" @click="setActive(2)">active 2</v-btn>

    <div>
      {{ active_style }}
      <tooth v-model:selection="selection" :active_style="active_style"></tooth>

    </div>
  </div>
</template>

<script setup lang="ts">
import { Button } from 'vant'
const showPicker = ref(false)
const d = ref({ groupChecked: ['ee'] })
const selection = ref<Number[]>([])
function test(v: Number[]) {
  selection.value = v
}
const columns = [
  { text: 'Delaware', value: 'Delaware' },
  { text: 'Florida', value: 'Florida' },
  { text: 'Georqia', value: 'Georqia' },
  { text: 'Indiana', value: 'Indiana' },
  { text: 'Maine', value: 'Maine' },
];
const onConfirm = (value) => {
  console.log(value)
  // d.value.result = value.value;
  showPicker.value = false;
};
const onSubmit = (values) => {
  console.log('submit', values);
  console.log('submit', d.value);

};
const $this = getCurrentInstance().proxy

function showconfirm() {
  $this.$dialog.confirm({
    title: 'Title',
    message: 'Content',
  })
    .then(() => {
      console.log('yes')
      // on confirm
    })
    .catch(() => {
      // on cancel
      console.log('no')

    });
}
function setActive(mode) {
  if (mode == 1) active_style.value = st1
  else active_style.value = st2
}


interface Style {
  fill?: string,
  stroke?: string,
  opacity?: number,
  parts: string[]
}

interface Operation {
  name: string,
  styles: Style[]
}



const st1: Operation = {
  name: 'op1',
  styles: [
    {
      fill: 'blue', stroke: 'black',
      parts: ['post'],
    },
    {
      fill: 'pink', stroke: 'pink',
      parts: ['canal'],
    },

    {
      fill: 'green', stroke: 'blue',
      parts: ['mesial', 'distal'],
    },
    {
      fill: 'pink', stroke: 'pink',
      parts: ['sub_mesial', 'sub_distal'],
    },

  ]

}
const st2: Operation = {
  name: 'op2',
  styles: [
    {
      fill: 'brown', stroke: 'red',
      parts: ['post'],
    },
    {
      fill: 'green', stroke: 'blue',
      parts: ['canal', 'distal'],
    },

  ]

}
const active_style = ref<Operation>()


</script>


