PrimeBlocks E-Commerce Edition is out now.
View Blocks

Component Scale
Input Style
Outlined
Filled
Ripple Effect

Free Themes
Built-in component themes created by the PrimeVue Theme Designer.

Bootstrap
Blue
Purple
Blue
Purple
Material Design
Indigo
Deep Purple
Indigo
Deep Purple
Material Design Compact
Indigo
Deep Purple
Indigo
Deep Purple
Tailwind
Tailwind Light
Fluent UI
Fluent Light
PrimeOne Design - 2022
NEW
Lara Light Indigo
Lara Light Blue
Lara Light Purple
Lara Light Teal
Lara Dark Indigo
Lara Dark Blue
Lara Dark Purple
Lara Dark Teal
PrimeOne Design - 2021
Saga Blue
Saga Green
Saga Orange
Saga Purple
Vela Blue
Vela Green
Vela Orange
Vela Purple
Arya Blue
Arya Green
Arya Orange
Arya Purple
Premium Themes
Premium themes are only available exclusively for PrimeVue Theme Designer subscribers and therefore not included in PrimeVue core.

Soho Light
Soho Dark
Viva Light
Viva Dark
Mira
Nano
Admin Templates
Beautifully crafted Vue CLI application templates by the PrimeTek design team.

Ultima
Atlantis
Freya
Ultima
Diamond
Sapphire
Serenity
Babylon
Avalon
Apollo
Roma
Prestige
Dropdown
Dropdown is used to select an item from a list of options.

Basic
Editable
Grouped
Advanced with Templating, Filtering and Clear Icon
Loading State
Virtual Scroll (100000 Items)
Virtual Scroll (100000 Items) and Lazy

<template>
  <div>
    <h5>Basic</h5>
    <Dropdown v-model="selectedCity1" :options="cities" optionLabel="name" optionValue="code"
      placeholder="Select a City" />

    <h5>Editable</h5>
    <Dropdown v-model="selectedCity2" :options="cities" optionLabel="name" :editable="true" />

    <h5>Grouped</h5>
    <Dropdown v-model="selectedGroupedCity" :options="groupedCities" optionLabel="label" optionGroupLabel="label"
      optionGroupChildren="items">
      <template #optiongroup="slotProps">
        <div class="flex align-items-center country-item">
          <img src="https://www.primefaces.org/wp-content/uploads/2020/05/placeholder.png" width="18" />
          <div>{{ slotProps.option.label }}</div>
        </div>
      </template>
    </Dropdown>

    <h5>Advanced with Templating, Filtering and Clear Icon</h5>
    <Dropdown v-model="selectedCountry" :options="countries" optionLabel="name" :filter="true"
      placeholder="Select a Country" :showClear="true">
      <template #value="slotProps">
        <div class="country-item country-item-value" v-if="slotProps.value">
          <img src="https://www.primefaces.org/wp-content/uploads/2020/05/placeholder.png" />
          <div>{{ slotProps.value.name }}</div>
        </div>
        <span v-else>
          {{ slotProps.placeholder }}
        </span>
      </template>
      <template #option="slotProps">
        <div class="country-item">
          <img src="https://www.primefaces.org/wp-content/uploads/2020/05/placeholder.png" />
          <div>{{ slotProps.option.name }}</div>
        </div>
      </template>
    </Dropdown>

    <h5>Loading State</h5>
    <Dropdown placeholder="Loading..." loading></Dropdown>

    <h5>Virtual Scroll (1000 Items)</h5>
    <Dropdown v-model="selectedItem1" :options="items" optionLabel="label" optionValue="value"
      :virtualScrollerOptions="{ itemSize: 38 }" placeholder="Select Item"></Dropdown>

    <h5>Virtual Scroll (1000 Items) and Lazy</h5>
    <Dropdown v-model="selectedItem2" :options="lazyItems" optionLabel="label" optionValue="value"
      :virtualScrollerOptions="{ lazy: true, onLazyLoad: onLazyLoad, itemSize: 38, showLoader: true, loading: loading, delay: 250 }"
      placeholder="Select Item">
      <template v-slot:loader="{ options }">
        <div class="flex align-items-center p-2" style="height: 38px">
          <Skeleton :width="options.even ? '60%' : '50%'" height="1rem" />
        </div>
      </template>
    </Dropdown>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const selectedCity1 = ref();
    const selectedCity2 = ref();
    const selectedCountry = ref();
    const selectedGroupedCity = ref();
    const selectedItem1 = ref();
    const selectedItem2 = ref();
    const loading = ref(false);
    const cities = ref([
      { name: 'New York', code: 'NY' },
      { name: 'Rome', code: 'RM' },
      { name: 'London', code: 'LDN' },
      { name: 'Istanbul', code: 'IST' },
      { name: 'Paris', code: 'PRS' }
    ]);
    const countries = ref([
      { name: 'Australia', code: 'AU' },
      { name: 'Brazil', code: 'BR' },
      { name: 'China', code: 'CN' },
      { name: 'Egypt', code: 'EG' },
      { name: 'France', code: 'FR' },
      { name: 'Germany', code: 'DE' },
      { name: 'India', code: 'IN' },
      { name: 'Japan', code: 'JP' },
      { name: 'Spain', code: 'ES' },
      { name: 'United States', code: 'US' }
    ]);
    const groupedCities = ref([
      {
        label: 'Germany', code: 'DE',
        items: [
          { label: 'Berlin', value: 'Berlin' },
          { label: 'Frankfurt', value: 'Frankfurt' },
          { label: 'Hamburg', value: 'Hamburg' },
          { label: 'Munich', value: 'Munich' }
        ]
      },
      {
        label: 'USA', code: 'US',
        items: [
          { label: 'Chicago', value: 'Chicago' },
          { label: 'Los Angeles', value: 'Los Angeles' },
          { label: 'New York', value: 'New York' },
          { label: 'San Francisco', value: 'San Francisco' }
        ]
      },
      {
        label: 'Japan', code: 'JP',
        items: [
          { label: 'Kyoto', value: 'Kyoto' },
          { label: 'Osaka', value: 'Osaka' },
          { label: 'Tokyo', value: 'Tokyo' },
          { label: 'Yokohama', value: 'Yokohama' }
        ]
      }
    ]);

    const items = ref(Array.from({ length: 100000 }, (_, i) => ({ label: `Item #${i}`, value: i })));
    const lazyItems = ref(Array.from({ length: 100000 }));

    return { selectedCity1, selectedCity2, selectedCountry, selectedGroupedCity, cities, countries, groupedCities, selectedItem1, selectedItem2, loading, items, lazyItems }
  },
  loadLazyTimeout: null,
  methods: {
    onLazyLoad(event) {
      this.loading = true;

      if (this.loadLazyTimeout) {
        clearTimeout(this.loadLazyTimeout);
      }

      //imitate delay of a backend call
      this.loadLazyTimeout = setTimeout(() => {
        const { first, last } = event;
        const lazyItems = [...this.lazyItems];

        for (let i = first; i < last; i++) {
          lazyItems[i] = { label: `Item #${i}`, value: i };
        }

        this.lazyItems = lazyItems;
        this.loading = false;
      }, Math.random() * 1000 + 250);
    }
  }
}
</script>

<style  scoped>
.p-dropdown {
  width: 14rem;
}

.country-item {
  img {
    width: 17px;
    margin-right: 0.5rem;
  }
}
</style>
PrimeVue 3.12.5 on Vue 3 by PrimeTek
