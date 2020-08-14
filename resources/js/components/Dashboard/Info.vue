<template>
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon" :class="color">
        <i class="text-white fas fa-fw" :class="icon"></i>
      </span>

      <div class="info-box-content">
        <span class="info-box-text">{{ title }}</span>
        <span class="info-box-number">{{ total }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "info",

  props: {
    title: { value: "", type: String },
    total: { value: 0, type: Number },
    icon: { value: "", type: String },
    color: { value: "", type: String },
  },
};
</script>
