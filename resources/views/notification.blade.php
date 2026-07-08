<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}} - AI Assistant</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include("links")
    <style>
        .ai-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .ai-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .ai-header-icon {
            width: 48px;
            height: 48px;
            background: rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .ai-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .ai-header p {
            font-size: 0.85rem;
            opacity: 0.7;
            margin: 0;
        }

        .suggestions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
            margin-bottom: 24px;
        }

        .suggestion-chip {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.88rem;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .suggestion-chip:hover {
            border-color: #f59e0b;
            background: #fffbeb;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
        }

        .suggestion-chip .chip-icon {
            color: #f59e0b;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .chat-form-wrapper {
            position: sticky;
            bottom: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.08);
            padding: 16px 20px;
            margin-bottom: 20px;
            z-index: 10;
        }

        .chat-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            outline: none;
            background: #f8fafc;
        }

        .chat-input:focus {
            border-color: #f59e0b;
            background: white;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .chat-submit {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .chat-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .chat-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .results-area {
            margin-bottom: 100px;
        }

        .result-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 16px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
        }

        .result-question {
            font-weight: 600;
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .question-icon {
            color: #f59e0b;
            flex-shrink: 0;
        }

        .humanized-answer {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            white-space: pre-wrap;
            line-height: 1.6;
            color: #334155;
            font-size: 0.92rem;
        }

        .sql-badge {
            display: inline-block;
            background: #1e293b;
            color: #94a3b8;
            border-radius: 6px;
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            margin-bottom: 8px;
            cursor: pointer;
            position: relative;
        }

        .sql-badge:hover {
            background: #334155;
        }

        .raw-results {
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
            display: none;
        }

        .raw-results.visible {
            display: block;
        }

        .toggle-raw-btn {
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.78rem;
            color: #64748b;
            cursor: pointer;
            margin-top: 6px;
        }

        .toggle-raw-btn:hover {
            background: #f8fafc;
            color: #334155;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }

        .loading-spinner.active {
            display: block;
        }

        .spinner-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f59e0b;
            margin: 0 3px;
            animation: bounce 0.6s infinite alternate;
        }

        .spinner-dot:nth-child(2) { animation-delay: 0.2s; }
        .spinner-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            to { transform: translateY(-8px); opacity: 0.5; }
        }

        .error-card {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .error-card .error-title {
            color: #dc2626;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 12px;
        }

        .suggestions-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .ai-container {
                padding: 12px;
            }
            .suggestions-grid {
                grid-template-columns: 1fr;
            }
            .chat-form {
                flex-direction: column;
            }
            .chat-submit {
                width: 100%;
            }
        }
         #word {
            width: 300px;
            padding: 10px;
            font-size: 18px;
        }

        pre {
            background: #111;
            color: #00ff00;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
            white-space: pre;
            font-family: Consolas, monospace;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        @include("sidenav")

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            <div class="ai-container">

                <div class="ai-header">
                    <div class="ai-header-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h2>AI Business Assistant</h2>
                        <p>Ask anything about your sales, inventory, customers, and finances</p>
                    </div>
                </div>  

                <div class="suggestions-label">Suggested Questions</div>
                <div class="suggestions-grid" id="suggestionsGrid">
                    @foreach($suggestedQuestions as $i => $q)
                        <button class="suggestion-chip" data-question="{{ e($q) }}">
                            <span class="chip-icon"><i class="fas fa-lightbulb"></i></span>
                            <span>{{ $q }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="chat-form-wrapper">
                    <form id="askForm" class="chat-form">
                        <input
                            type="text"
                            name="question"
                            id="questionInput"
                            class="chat-input"
                            placeholder="Ask about sales, stock, customers, revenue..."
                            autocomplete="off"
                            value="{{ old('question') }}"
                        >
                        <button type="submit" class="chat-submit" id="submitBtn">
                            <i class="fas fa-paper-plane me-1"></i> Ask
                        </button>
                    </form>
                </div>

                <div class="loading-spinner" id="loadingSpinner">
                    <div class="spinner-dot"></div>
                    <div class="spinner-dot"></div>
                    <div class="spinner-dot"></div>
                </div>

                <div class="results-area" id="resultsArea"></div>

            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    const $form = $('#askForm');
    const $input = $('#questionInput');
    const $btn = $('#submitBtn');
    const $spinner = $('#loadingSpinner');
    const $results = $('#resultsArea');

    $('.suggestion-chip').on('click', function() {
        const q = $(this).data('question');
        $input.val(q).focus();
        $form.submit();
    });

    $form.on('submit', function(e) {
        e.preventDefault();

        const question = $.trim($input.val());
        if (!question) return;

        $btn.prop('disabled', true);
        $spinner.addClass('active');
        $results.empty();

        $.ajax({
            url: '{{ route("ai-agent.ask") }}',
            method: 'POST',
            data: {
                question: question,
                _token: $('meta[name=csrf-token]').attr('content')
            },
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.error) {
                    $results.append(`
                        <div class="error-card">
                            <div class="error-title"><i class="fas fa-exclamation-circle me-1"></i> Unable to answer</div>
                            <div>${escapeHtml(response.error)}</div>
                        </div>
                    `);
                    return;
                }

                let html = '<div class="result-card">';

                html += '<div class="result-question">';
                html += '<span class="question-icon"><i class="fas fa-user-circle"></i></span>';
                html += '<span>' + escapeHtml(response.question) + '</span>';
                html += '</div>';

                if (response.humanized) {
                    html += '<div class="humanized-answer">' + escapeHtml(response.humanized) + '</div>';
                }

                if (response.sql) {
                    html += '<details style="margin-top:10px;">';
                    html += '<summary class="toggle-raw-btn">View SQL Query</summary>';
                    html += '<div class="sql-badge" style="margin-top:6px;display:block;">' + escapeHtml(response.sql) + '</div>';
                    html += '</details>';
                }

                if (response.results && response.results.length > 0) {
                    html += '<details style="margin-top:8px;">';
                    html += '<summary class="toggle-raw-btn">View Raw Data (' + response.results.length + ' rows)</summary>';
                    html += '<div class="raw-results">' + escapeHtml(JSON.stringify(response.results, null, 2)) + '</div>';
                    html += '</details>';
                }

                html += '</div>';
                $results.append(html);

                $('html, body').animate({
                    scrollTop: $results.offset().top - 80
                }, 300);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.error || 'Something went wrong. Please try again.';
                $results.append(`
                    <div class="error-card">
                        <div class="error-title"><i class="fas fa-exclamation-circle me-1"></i> Error</div>
                        <div>${escapeHtml(msg)}</div>
                    </div>
                `);
            },
            complete: function() {
                $btn.prop('disabled', false);
                $spinner.removeClass('active');
            }
        });
    });

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
});
</script>
</body>
</html>
