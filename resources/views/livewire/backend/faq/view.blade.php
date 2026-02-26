<div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover">
                <colgroup>
                    <col width="20%">
                    <col width="80%">
                </colgroup>
                <tbody>
                    <tr>
                        <th style="vertical-align:top">Category</th>
                        <td>{{ $faq->faqCategory->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th style="vertical-align:top">Question</th>
                        <td>
                            {{ $faq->question ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th style="vertical-align:top">Answer</th>
                        <td>
                            <div class="faq-answer">
                                {{ $faq->answer ?? 'N/A' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $faq->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($faq->status) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
