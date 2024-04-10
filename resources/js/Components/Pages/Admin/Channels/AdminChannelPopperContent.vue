<template>
  <Popper hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0" placement="bottom">
    <template #content>
      <slot name="content"></slot> <!-- Dynamic content -->
    </template>
    <slot name="activator"></slot>
  </Popper>
</template>
<style scoped>
#tooltip {
  background: #333;
  color: white;
  font-weight: bold;
  padding: 4px 8px;
  font-size: 13px;
  border-radius: 4px;
}

:deep(.popper) {
  background: #333333;
  padding: 2px;
  border-radius: 20px;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
}

:deep(.popper #arrow::before) {
  background: #333333;
}

:deep(.popper:hover),
:deep(.popper:hover > #arrow::before) {
  background: #333333;
}
</style>