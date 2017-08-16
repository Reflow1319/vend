<template>
  <div class="form-group">
    <label>{{ label }}</label>

    <input type="text"
           :placeholder="placeholder ? $t(placeholder) : ''"
           :value="value"
           :class="[{'input-lg': large}, 'form-control']"
           @change="$emit('input', $event.target.value)"
           v-if="type === 'text' || ! type">

    <select @change="$emit('input', $event.target.value)" v-if="type === 'select'" class="form-control">
      <option :value="v" :selected="v === value" v-for="(l, v) in options">{{ l }}</option>
    </select>

  </div>
</template>

<script>
  export default {
    props: [
      'label',
      'placeholder',
      'value',
      'large',
      'type',
      'options'
    ]
  }
</script>