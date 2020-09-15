<template>
  <v-app>

    <v-timeline dense
      v-for="( activity, index ) in activities" :key="index"
      >
    <v-chip
      class="ma-2">
      {{ index }}
    </v-chip>
      <v-slide-x-reverse-transition
        group
        hide-on-leave
        >
        <v-timeline-item
          v-for="ac in activity"
          :key="ac.id"
          color="info"
          small
          fill-dot
          >
          <v-alert
            :value="true"
            color="primary"
            icon="mdi-information"
            class="white--text"
            >
            <p><b>{{ __('number_transaction') }}: </b><span>{{ ac.number_transaction }}</span></p>
            <p><b>{{ __('transaction_date') }}: </b><span>{{ ac.transaction_date }}</span></p>
            <p><b>{{ __('total_price') }}: </b><span>{{ ac.total_price }}</span></p>
            <p><b>{{ __('total_qty') }}: </b><span>{{ ac.total_qty }}</span></p>
            <p><b>{{ __('total_profit') }}: </b><span>{{ ac.total_profit }}</span></p>
            </v-alert>
        </v-timeline-item>
      </v-slide-x-reverse-transition>
    </v-timeline>
  </v-app>
</template>

<script>
  import { mapState, mapActions } from 'vuex';

  const COLORS = [
    'info',
    'warning',
    'error',
    'success',
    "deep-purple accent-4"
  ]
  const ICONS = {
    info: 'mdi-information',
    warning: 'mdi-alert',
    error: 'mdi-alert-circle',
    success: 'mdi-check-circle',
  }

  export default {
    name: 'Activity',
    data: () => ({
      interval: null,
      items: [
        {
          id: 1,
          color: 'info',
          icon: ICONS.info,
        },
      ],
      nonce: 2,
    }),

    computed: mapState('activity', {
      activities: state => state.activities
    }),

    beforeDestroy () {
      this.stop()
    },

    methods: {
      ...mapActions('activity', [
        'getActivity'
      ]),
      addEvent () {
        let { color, icon } = this.genAlert()

        const previousColor = this.items[0].color

        while (previousColor === color) {
          color = this.genColor()
        }

        this.items.unshift({
          id: this.nonce++,
          color,
          icon,
        })

        if (this.nonce > 6) {
          this.items.pop()
        }
      },
      genAlert () {
        const color = this.genColor()

        return {
          color,
          icon: this.genIcon(color),
        }
      },
      genColor () {
        return COLORS[Math.floor(Math.random() * 3)]
      },
      genIcon (color) {
        return ICONS[color]
      },
      start () {
        this.interval = setInterval(this.addEvent, 3000)
      },
      stop () {
        clearInterval(this.interval)
        this.interval = null
      },
    },

    mounted() {
      this.getActivity()
    }
  }
</script>
