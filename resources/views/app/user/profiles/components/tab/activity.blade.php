<!-- The timeline -->
<div class="timeline timeline-inverse">
  @foreach ($data['activity'] as $key => $activities)
    <!-- timeline time label -->
    <div class="time-label">
      <span class="bg-info">
        {{ $key }}
      </span>
    </div>
    <!-- /.timeline-label -->
    @foreach ($activities as $activity)

    <!-- timeline item -->
    <div>
      <i class="fas fa-pencil bg-primary"></i>

      <div class="timeline-item">
        <span class="time"><i class="far fa-clock"></i> {{ $activity->created_at->format('H:i:s') }}</span>

        <h3 class="timeline-header"><a href="/{{ $activity->url }}">{{ ucfirst($activity->info) . ' ' . $activity->url }}</a> </h3>
        <div class="timeline-body">
          @foreach ($activity->getProperty() as $k => $property)
            @foreach (Arr::except($property, ['id', 'created_at', 'updated_at']) as $key => $pro)
              @if (!is_array($pro))
                <p class="m-0">{{ $key . ': '. $pro }}</p>
              @endif
            @endforeach
          @endforeach
        </div>
      </div>
    </div>
    @endforeach
    <!-- END timeline item -->
  @endforeach
    <div>
      <i class="far fa-clock bg-gray"></i>
    </div>
</div>
